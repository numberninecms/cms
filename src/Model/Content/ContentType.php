<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Content;

use NumberNine\Entity\ContentEntity;
use NumberNine\Security\Capabilities;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

final class ContentType
{
    private string $entityClassName;
    private string $name;
    private string $permalink;
    private string $icon;
    private bool $public;
    private bool $shownInMenu;
    private ContentTypeLabels $labels;
    private array $capabilities;
    private ?string $editorExtension;

    public function __construct(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        foreach ($options as $key => $value) {
            $property = u($key)->camel()->toString();

            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabels(): ContentTypeLabels
    {
        return $this->labels;
    }

    public function getPermalink(): string
    {
        return $this->permalink;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function isShownInMenu(): bool
    {
        return $this->shownInMenu;
    }

    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    public function hasCapability(string $capability): bool
    {
        return \in_array($capability, $this->capabilities, true);
    }

    public function getMappedCapability(string $capability): string
    {
        return $this->capabilities[$capability] ?? $capability;
    }

    public function getMappedCapabilities(array $capabilities): array
    {
        $result = [];

        foreach ($capabilities as $capability) {
            $result[] = $this->getMappedCapability($capability);
        }

        return $result;
    }

    public function getEditorExtension(): ?string
    {
        return $this->editorExtension;
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'labels' => null,
                'permalink' => '/{slug}',
                'icon' => 'pen-nib',
                'public' => true,
                'shown_in_menu' => true,
                'capabilities' => [],
                'editor_extension' => null,
            ]
        );

        $resolver->setRequired(['name', 'entity_class_name']);

        $resolver
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('entity_class_name', 'string')
            ->setAllowedTypes('labels', ['null', ContentTypeLabels::class])
            ->setAllowedTypes('permalink', 'string')
            ->setAllowedTypes('icon', 'string')
            ->setAllowedTypes('public', 'bool')
            ->setAllowedTypes('shown_in_menu', 'bool')
            ->setAllowedTypes('capabilities', 'string[]')
            ->setAllowedTypes('editor_extension', ['null', 'string'])
        ;

        $this->configureOptionsNormalizers($resolver);
    }

    private function configureOptionsNormalizers(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer(
            'entity_class_name',
            static function (Options $options, $value) {
                if (!class_exists($value) || !is_subclass_of($value, ContentEntity::class)) {
                    throw new InvalidOptionsException(
                        sprintf(
                            '"entity_class_name" option must be a fully qualified class name which extends ' .
                            'NumberNine\Entity\ContentEntity. "%s" is invalid.',
                            $value
                        )
                    );
                }

                return $value;
            }
        );

        $resolver->setNormalizer(
            'name',
            static function (Options $options, $value) {
                return u($value)->snake()->toString();
            }
        );

        $resolver->setNormalizer(
            'labels',
            static function (Options $options, $value) {
                return $value ?? new ContentTypeLabels($options['name']);
            }
        );

        $resolver->setNormalizer(
            'capabilities',
            function (Options $options, $value): array {
                return $this->createCapabilitiesArray($options, $value);
            }
        );
    }

    /**
     * @param mixed $value
     */
    private function createCapabilitiesArray(Options $options, $value): array
    {
        $capabilities = array_replace($this->mapCapabilities($options), $value);

        return array_reduce(
            $capabilities,
            static function ($array, $value) use ($capabilities): array {
                $key = array_search($value, $capabilities, true);

                if (is_numeric($key)) {
                    $array[$value] = $value;
                    unset($array[$key]);
                } else {
                    $array[$key] = $value;
                }

                return $array;
            },
            []
        );
    }

    private function mapCapabilities(Options $options): array
    {
        $defaultCapabilities = [
            Capabilities::READ_POSTS,
            Capabilities::READ_PRIVATE_POSTS,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_PRIVATE_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
            Capabilities::EDIT_PUBLISHED_POSTS,
            Capabilities::PUBLISH_POSTS,
            Capabilities::DELETE_POSTS,
            Capabilities::DELETE_PRIVATE_POSTS,
            Capabilities::DELETE_OTHERS_POSTS,
            Capabilities::DELETE_PUBLISHED_POSTS,
        ];

        $mappedCapabilities = array_map(
            static fn ($capability): string => str_replace(
                'posts',
                u($options['labels']->getPluralName())->snake()->toString(),
                $capability
            ),
            $defaultCapabilities
        );

        return array_combine($defaultCapabilities, $mappedCapabilities);
    }
}
