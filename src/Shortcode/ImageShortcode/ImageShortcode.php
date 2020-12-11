<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ImageShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\MediaFileRepository;

/**
 * @Shortcode(name="image", label="Image", editable=true, icon="image")
 * @Shortcode\ExclusionPolicy("none")
 */
final class ImageShortcode extends AbstractShortcode
{
    private MediaFileRepository $mediaFileRepository;

    public function __construct(MediaFileRepository $mediaFileRepository)
    {
        $this->mediaFileRepository = $mediaFileRepository;
    }

    private function getImage(ImageShortcodeData $data): ?MediaFile
    {
        if (!$data->getId() && !$data->getFromTitle()) {
            return null;
        }

        if ($data->getId()) {
            return $this->mediaFileRepository->find($data->getId());
        }

        return $this->mediaFileRepository->findOneBy(['title' => $data->getFromTitle()]);
    }

    /**
     * @param ImageShortcodeData $data
     */
    public function process($data): void
    {
        $data->setImage($this->getImage($data));
    }
}
