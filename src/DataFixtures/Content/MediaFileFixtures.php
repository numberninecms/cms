<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures\Content;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\DataFixtures\FixtureSettings;
use NumberNine\Entity\MediaFile;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Content\ContentService;
use NumberNine\Media\MediaFileFactory;

final class MediaFileFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private MediaFileFactory $mediaFileFactory;

    public function __construct(MediaFileFactory $mediaFileFactory, ContentService $contentService, Generator $faker)
    {
        parent::__construct($contentService, $faker);
        $this->mediaFileFactory = $mediaFileFactory;
    }

    public function loadData(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference(User::class . '_administrator');
        $logo = $this->mediaFileFactory->createMediaFileFromFilename(__DIR__ . '/../../../assets/images/NumberNine512_slogan.png', $admin, false, true, false);
        $logo->setTitle('NumberNine Logo');
        $this->addReference(MediaFile::class . '_logo', $logo);

        // Post featured images
        $this->createManyContentEntity(
            'media_file',
            FixtureSettings::POSTS_COUNT,
            function (MediaFile &$mediaFile, int $i) {
                /** @var Post $post */
                $post = $this->getReference(Post::class . '_post_' . $i);

                $mediaFile = $this->mediaFileFactory->createMediaFileFromFilename(
                    $this->faker->blogFeaturedImage($post->getTerms('category')[0]->getName()),
                    $post->getAuthor(),
                    false,
                    false,
                    false
                );

                $mediaFile
                    ->setTitle($post->getTitle() . ' Featured Image')
                    ->setStatus(PublishingStatusInterface::STATUS_PUBLISH);

                $post->setFeaturedImage($mediaFile);
            }
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class, ContentEntityTermFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['content'];
    }
}
