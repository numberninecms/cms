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
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use wapmorgan\MediaFile\MediaFile as MediaFileMetadataReader;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\Media\FileDescriptor;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class MediaFileFactory
{
    private EntityManagerInterface $entityManager;
    private ImageProcessor $imageProcessor;
    private ImageSizeStore $imageSizeStore;
    private Security $security;
    private ExtendedSluggerInterface $slugger;
    private string $uploadPath;
    private string $absoluteUploadPath;
    private string $datedAbsoluteUploadPath;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @param ExtendedSluggerInterface $slugger
     * @param ImageProcessor $imageProcessor
     * @param ImageSizeStore $imageSizeStore
     * @param string $uploadPath
     * @param string $publicPath
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        ExtendedSluggerInterface $slugger,
        ImageProcessor $imageProcessor,
        ImageSizeStore $imageSizeStore,
        string $uploadPath,
        string $publicPath
    ) {
        $this->entityManager = $entityManager;
        $this->imageProcessor = $imageProcessor;
        $this->imageSizeStore = $imageSizeStore;
        $this->security = $security;
        $this->slugger = $slugger;
        $this->uploadPath = $uploadPath;
        $this->absoluteUploadPath = $publicPath . $this->uploadPath;
        $this->datedAbsoluteUploadPath = $publicPath . $uploadPath . '/' . date('Y/m');
    }

    /**
     * @param string $filename
     * @param UserInterface $user
     * @param bool $move
     * @param bool $overwrite
     * @param bool $flush
     * @return MediaFile
     */
    public function createMediaFileFromFilename(string $filename, UserInterface $user, bool $move = false, bool $overwrite = true, bool $flush = true): MediaFile
    {
        $file = $this->getFileDescriptor($filename);
        $this->moveOrCopy($file, $move, $overwrite);

        return $this->createMediaFile($file, $user, $flush);
    }

    /**
     * @param FileDescriptor $file
     * @param UserInterface $user
     * @param bool $move
     * @param bool $overwrite
     * @param bool $flush
     * @return MediaFile
     */
    public function createMediaFileFromFileDescriptor(FileDescriptor $file, UserInterface $user, bool $move = false, bool $overwrite = true, bool $flush = true): MediaFile
    {
        $this->moveOrCopy($file, $move, $overwrite);

        return $this->createMediaFile($file, $user, $flush);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return MediaFile
     */
    public function createMediaFileFromUploadedFile(UploadedFile $uploadedFile): MediaFile
    {
        if (!$uploadedFile->isValid()) {
            throw new RuntimeException('Uploaded file is invalid.');
        }

        $file = $this->getFileDescriptor((string)$uploadedFile->getClientOriginalName());

        $uploadedFile->move($file->getUploadDirectory(), pathinfo($file->getNewFilename(), PATHINFO_BASENAME));

        return $this->createMediaFile($file, $this->security->getUser());
    }

    /**
     * @param string $filename
     * @return FileDescriptor
     */
    private function getFileDescriptor(string $filename): FileDescriptor
    {
        if (!file_exists($this->datedAbsoluteUploadPath) && !mkdir($this->datedAbsoluteUploadPath, 0755, true) && !is_dir($this->datedAbsoluteUploadPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created.', $this->datedAbsoluteUploadPath));
        }

        $slugifiedFilename = $this->slugger->getUniqueFilenameSlug($this->datedAbsoluteUploadPath, $filename);

        return (new FileDescriptor())
            ->setNewFilename($this->datedAbsoluteUploadPath . '/' . $slugifiedFilename)
            ->setSlugifiedFilename($slugifiedFilename)
            ->setOriginalFilename($filename);
    }

    /**
     * @param FileDescriptor $file
     * @param bool $move
     * @param bool $overwrite
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
            throw new FileException(sprintf('Failed to move or copy "%s" to "%s".', $file->getOriginalFilename(), $file->getNewFilename()));
        }
    }

    /**
     * @param FileDescriptor $file
     * @param UserInterface|null $user
     * @param bool $flush
     * @return MediaFile
     */
    private function createMediaFile(FileDescriptor $file, ?UserInterface $user, bool $flush = true): MediaFile
    {
        $mimeTypes = new MimeTypes();
        $mimeType = (string)$mimeTypes->guessMimeType($file->getNewFilename());

        $mediaFile = (new MediaFile())
            ->setCustomType('media_file')
            ->setFileSize(filesize($file->getNewFilename()))
            ->setAuthor($user)
            ->setTitle(pathinfo($file->getOriginalFilename(), PATHINFO_FILENAME))
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setSlug(pathinfo($file->getSlugifiedFilename(), PATHINFO_FILENAME))
            ->setPath($this->uploadPath . str_replace([realpath($this->absoluteUploadPath), '\\'], ['', '/'], (string)realpath($file->getNewFilename())))
            ->setMimeType($mimeType);

        if (strpos($mimeType, 'image') === 0) {
            $processedImage = $this->imageProcessor->processImage($file->getNewFilename(), $this->imageSizeStore->getImageSizes());
            $size = $processedImage->getImage()->getSize();

            $mediaFile
                ->setWidth($size->getWidth())
                ->setHeight($size->getHeight())
                ->setExif($processedImage->getExif())
                ->setSizes($processedImage->getSizes());
        } elseif (strpos($mimeType, 'video') === 0) {
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
            } catch (Exception $e) {
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
