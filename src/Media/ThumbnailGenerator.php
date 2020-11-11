<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Media;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\MediaFile;
use NumberNine\Repository\MediaFileRepository;
use NumberNine\Media\ImageSizeStore;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

final class ThumbnailGenerator
{
    private EntityManagerInterface $entityManager;
    private MediaFileRepository $mediaFileRepository;
    private ImageProcessor $imageProcessor;
    private ImageSizeStore $imageSizeStore;
    private ?SymfonyStyle $io = null;
    private string $publicPath;

    /**
     * @param EntityManagerInterface $entityManager
     * @param MediaFileRepository $mediaFileRepository
     * @param ImageProcessor $imageProcessor
     * @param ImageSizeStore $imageSizeStore
     * @param string $publicPath
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MediaFileRepository $mediaFileRepository,
        ImageProcessor $imageProcessor,
        ImageSizeStore $imageSizeStore,
        string $publicPath
    ) {
        $this->entityManager = $entityManager;
        $this->imageProcessor = $imageProcessor;
        $this->publicPath = $publicPath;
        $this->imageSizeStore = $imageSizeStore;
        $this->mediaFileRepository = $mediaFileRepository;
    }

    /**
     * @param SymfonyStyle $symfonyStyle
     */
    public function setIo(SymfonyStyle $symfonyStyle): void
    {
        $this->io = $symfonyStyle;
    }

    /**
     * Regenerates all images variations in media library
     */
    public function regenerateImageVariations(): void
    {
        $query = $this->mediaFileRepository->findImagesQuery();
        $iterableResult = $query->iterate();

        $counter = 0;

        foreach ($iterableResult as $row) {
            /** @var MediaFile $mediaFile */
            $mediaFile = $row[0];

            $finder = new Finder();
            $finder
                ->files()
                ->name('@' . pathinfo((string)$mediaFile->getPath(), PATHINFO_BASENAME) . '.+@')
                ->in($this->publicPath . dirname((string)$mediaFile->getPath()));

            if (iterator_count($finder) > 0) {
                $mediaFile->setSizes([]);

                foreach ($finder as $file) {
                    unlink((string)$file->getRealPath());
                }
            }

            $processedImage = $this->imageProcessor->processImage(
                $this->publicPath . $mediaFile->getPath(),
                $this->imageSizeStore->getImageSizes()
            );
            $mediaFile->setSizes($processedImage->getSizes());
            $this->entityManager->persist($mediaFile);

            if (++$counter % 10 === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                if ($this->io && $counter % 100 === 0) {
                    $this->io->write('.');
                }
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        if ($this->io) {
            $this->io->writeln('.');
        }
    }
}
