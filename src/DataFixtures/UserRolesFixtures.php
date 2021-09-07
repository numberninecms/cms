<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\Entity\UserRole;
use NumberNine\Security\Capabilities;
use NumberNine\Security\CapabilityGenerator;

final class UserRolesFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private CapabilityGenerator $capabilityGenerator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $banned = (new UserRole())
            ->setName('Banned')
        ;

        $subscriber = (new UserRole())
            ->setName('Subscriber')
            ->setCapabilities(
                [Capabilities::READ, ...$this->capabilityGenerator->generateMappedSubscriberCapabilities('post')]
            )
        ;

        $contributor = (new UserRole())
            ->setName('Contributor')
            ->setCapabilities(
                [
                    Capabilities::READ,
                    Capabilities::ACCESS_ADMIN,
                    ...$this->capabilityGenerator->generateMappedContributorCapabilities('post'),
                ]
            )
        ;

        $author = (new UserRole())
            ->setName('Author')
            ->setCapabilities(
                [
                    Capabilities::READ,
                    Capabilities::ACCESS_ADMIN,
                    Capabilities::UPLOAD_FILES,
                    ...$this->capabilityGenerator->generateMappedAuthorCapabilities('post'),
                    ...$this->capabilityGenerator->generateMappedAuthorCapabilities('media_file'),
                ]
            )
        ;

        $editor = (new UserRole())
            ->setName('Editor')
            ->setCapabilities(
                [
                    Capabilities::READ,
                    Capabilities::ACCESS_ADMIN,
                    Capabilities::UPLOAD_FILES,
                    Capabilities::MANAGE_CATEGORIES,
                    Capabilities::MODERATE_COMMENTS,
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('post'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('page'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('block'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('media_file'),
                ]
            )
        ;

        $administrator = (new UserRole())
            ->setName('Administrator')
            ->setCapabilities(
                [
                    Capabilities::READ,
                    Capabilities::ACCESS_ADMIN,
                    Capabilities::UPLOAD_FILES,
                    Capabilities::MANAGE_CATEGORIES,
                    Capabilities::MODERATE_COMMENTS,
                    Capabilities::MANAGE_OPTIONS,
                    Capabilities::LIST_USERS,
                    Capabilities::PROMOTE_USERS,
                    Capabilities::REMOVE_USERS,
                    Capabilities::EDIT_USERS,
                    Capabilities::ADD_USERS,
                    Capabilities::CREATE_USERS,
                    Capabilities::DELETE_USERS,
                    Capabilities::MANAGE_ROLES,
                    Capabilities::CUSTOMIZE,
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('post'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('page'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('block'),
                    ...$this->capabilityGenerator->generateMappedEditorCapabilities('media_file'),
                ]
            )
        ;

        $this->setReference(UserRole::class . '_subscriber', $subscriber);
        $this->setReference(UserRole::class . '_contributor', $contributor);
        $this->setReference(UserRole::class . '_author', $author);
        $this->setReference(UserRole::class . '_editor', $editor);
        $this->setReference(UserRole::class . '_administrator', $administrator);

        $manager->persist($subscriber);
        $manager->persist($contributor);
        $manager->persist($author);
        $manager->persist($editor);
        $manager->persist($administrator);
        $manager->persist($banned);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['numbernine_core'];
    }
}
