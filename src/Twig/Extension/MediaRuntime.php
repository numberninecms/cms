<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\MediaFile;
use NumberNine\Exception\InvalidMimeTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\Link;
use Twig\Error\RuntimeError;
use Twig\Extension\RuntimeExtensionInterface;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

final class MediaRuntime implements RuntimeExtensionInterface
{
    private ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * @param ContentEntity $contentEntity
     * @param string $size
     * @param array $attributes
     * @return string
     * @throws RuntimeError
     * @throws InvalidMimeTypeException
     */
    public function getFeaturedImage(
        ContentEntity $contentEntity,
        string $size = 'large',
        array $attributes = []
    ): string {
        if (!method_exists($contentEntity, 'getFeaturedImage')) {
            throw new RuntimeError(sprintf(
                "Entity of type %s doesn't have a \$featuredImage property.",
                get_class($contentEntity)
            ));
        }

        /** @var ?MediaFile $featuredImage */
        $featuredImage = $contentEntity->getFeaturedImage();

        if (!$featuredImage) {
            return '';
        }

        $attributes['alt'] ??= $contentEntity->getTitle();

        return $this->getImage($featuredImage, $size, $attributes);
    }

    /**
     * @param MediaFile $mediaFile
     * @param string $size
     * @return string
     * @throws InvalidMimeTypeException
     */
    public function getImageUrl(?MediaFile $mediaFile, string $size = null): string
    {
        if (!$mediaFile) {
            return '';
        }

        if (!$mediaFile->isImage()) {
            throw new InvalidMimeTypeException($mediaFile, 'image');
        }

        return $size ? $mediaFile->getSizePath($size) : (string)$mediaFile->getPath();
    }

    /**
     * @param MediaFile $mediaFile
     * @param string $size
     * @param array $attributes
     * @return string
     * @throws InvalidMimeTypeException
     */
    public function getImage(MediaFile $mediaFile, string $size = 'large', array $attributes = []): string
    {
        if (!$mediaFile->isImage()) {
            throw new InvalidMimeTypeException($mediaFile, 'image');
        }

        $attributes['alt'] ??= $mediaFile->getTitle();

        $sizeInfo = $mediaFile->getSize($size);

        $assetPath = $sizeInfo ? $mediaFile->getSizePath($size) : (string)$mediaFile->getPath();

        if ($this->request) {
            $linkProvider = $this->request->attributes->get('_links', new GenericLinkProvider());
            $this->request->attributes->set('_links', $linkProvider->withLink(
                (new Link('preload', $assetPath))->withAttribute('as', 'image')
            ));
        }

        return sprintf(
            '<img src="%s" width="%d" height="%d" %s>',
            $assetPath,
            $sizeInfo['width'] ?? $mediaFile->getWidth(),
            $sizeInfo['height'] ?? $mediaFile->getHeight(),
            array_implode_associative($attributes, ' ', '=', '', '"'),
        );
    }
}
