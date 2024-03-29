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
use Exception;
use NumberNine\Common\Util\StringUtil\ExtendedSluggerInterface;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\Media\FileDescriptor;
use RuntimeException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use wapmorgan\MediaFile\MediaFile as MediaFileMetadataReader;

final class MediaFileFactory
{
    private string $absoluteUploadPath;
    private string $datedAbsoluteUploadPath;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private ExtendedSluggerInterface $slugger,
        private ImageProcessor $imageProcessor,
        private ImageSizeStore $imageSizeStore,
        private string $uploadPath,
        string $publicPath
    ) {
        $this->absoluteUploadPath = $publicPath . $this->uploadPath;
        $this->datedAbsoluteUploadPath = $publicPath . $uploadPath . '/' . date('Y/m');
    }

    public function createMediaFileFromFilename(
        string $filename,
        ?UserInterface $user,
        bool $move = false,
        bool $overwrite = true,
        bool $flush = true
    ): MediaFile {
        $file = $this->getFileDescriptor($filename);
        $this->moveOrCopy($file, $move, $overwrite);

        return $this->createMediaFile($file, $user, $flush);
    }

    public function createMediaFileFromFileDescriptor(
        FileDescriptor $file,
        ?UserInterface $user,
        bool $move = false,
        bool $overwrite = true,
        bool $flush = true
    ): MediaFile {
        $this->moveOrCopy($file, $move, $overwrite);

        return $this->createMediaFile($file, $user, $flush);
    }

    public function createMediaFileFromUploadedFile(UploadedFile $uploadedFile): MediaFile
    {
        if (!$uploadedFile->isValid()) {
            throw new RuntimeException('Uploaded file is invalid.');
        }

        $file = $this->getFileDescriptor((string) $uploadedFile->getClientOriginalName());

        $uploadedFile->move($file->getUploadDirectory(), pathinfo($file->getNewFilename(), PATHINFO_BASENAME));

        return $this->createMediaFile($file, $this->security->getUser());
    }

    private function getFileDescriptor(string $filename): FileDescriptor
    {
        if (
            !file_exists($this->datedAbsoluteUploadPath)
            && !mkdir($this->datedAbsoluteUploadPath, 0755, true)
            && !is_dir($this->datedAbsoluteUploadPath)
        ) {
            throw new RuntimeException(sprintf('Directory "%s" was not created.', $this->datedAbsoluteUploadPath));
        }

        $slugifiedFilename = $this->slugger->getUniqueFilenameSlug($this->datedAbsoluteUploadPath, $filename);

        return (new FileDescriptor())
            ->setNewFilename($this->datedAbsoluteUploadPath . '/' . $slugifiedFilename)
            ->setSlugifiedFilename($slugifiedFilename)
            ->setOriginalFilename($filename)
        ;
    }

    /**
     * @return never
     */
    private function moveOrCopy(FileDescriptor $file, bool $move = false, bool $overwrite = true): void
    {
        if (!file_exists($file->getOriginalFilename())) {
            throw new FileNotFoundException(null, 0, null, $file->getOriginalFilename());
        }

        $result = true;
        $performAction = $overwrite || !file_exists($file->getNewFilename());

        if ($move && $performAction) {
            $result = rename($file->getOriginalFilename(), $file->getNewFilename());
        } elseif (!$move && $performAction) {
            $result = copy($file->getOriginalFilename(), $file->getNewFilename());
        }

        if (!$result) {
            throw new FileException(sprintf(
                'Failed to move or copy "%s" to "%s".',
                $file->getOriginalFilename(),
                $file->getNewFilename()
            ));
        }
    }

    private function createMediaFile(FileDescriptor $file, ?UserInterface $user, bool $flush = true): MediaFile
    {
        $mimeTypes = new MimeTypes();
        $mimeType = (string) $mimeTypes->guessMimeType($file->getNewFilename());

        $mediaFile = (new MediaFile())
            ->setCustomType('media_file')
            ->setFileSize(filesize($file->getNewFilename()))
            ->setAuthor($user)
            ->setTitle(pathinfo($file->getOriginalFilename(), PATHINFO_FILENAME))
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setSlug(pathinfo($file->getSlugifiedFilename(), PATHINFO_FILENAME))
            ->setPath($this->uploadPath . str_replace(
                [realpath($this->absoluteUploadPath), '\\'],
                ['', '/'],
                (string) realpath($file->getNewFilename())
            ))
            ->setMimeType($mimeType)
        ;

        if (str_starts_with($mimeType, 'image')) {
            $processedImage = $this->imageProcessor->processImage(
                $file->getNewFilename(),
                $this->imageSizeStore->getImageSizes()
            );
            $size = $processedImage->getImage()->getSize();

            $mediaFile
                ->setWidth($size->getWidth())
                ->setHeight($size->getHeight())
                ->setExif($processedImage->getExif())
                ->setSizes($processedImage->getSizes())
            ;
        } elseif (str_starts_with($mimeType, 'video')) {
            try {
                $video = MediaFileMetadataReader::open($file->getNewFilename())->getVideo();

                if ($video) {
                    if ($video->getWidth()) {
                        $mediaFile->setWidth($video->getWidth());
                    }

                    if ($video->getHeight()) {
                        $mediaFile->setHeight($video->getHeight());
                    }

                    if ($video->getLength()) {
                        $mediaFile->setDuration($video->getLength());
                    }
                }
            } catch (Exception) {
                // just ignore and don't add video metadata
            }
        }

        $this->entityManager->persist($mediaFile);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $mediaFile;
    }
}
