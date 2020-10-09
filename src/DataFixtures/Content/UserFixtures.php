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
use NumberNine\Entity\User;
use NumberNine\Entity\UserRole;
use NumberNine\Security\UserFactory;
use NumberNine\Content\ContentService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends BaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private UserFactory $userFactory;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(ContentService $contentService, Generator $faker, UserFactory $userFactory, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($contentService, $faker);
        $this->userFactory = $userFactory;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager): void
    {
        $administrator = $this->userFactory->createUser('admin', 'administrator@numbernine-fakedomain.com', 'password', [$this->getReference(UserRole::class . '_administrator')], false);
        $editor = $this->userFactory->createUser('editor', 'editor@numbernine-fakedomain.com', 'password', [$this->getReference(UserRole::class . '_editor')], false);
        $author = $this->userFactory->createUser('author', 'author@numbernine-fakedomain.com', 'password', [$this->getReference(UserRole::class . '_author')], false);
        $contributor = $this->userFactory->createUser('contributor', 'contributor@numbernine-fakedomain.com', 'password', [$this->getReference(UserRole::class . '_contributor')], false);
        $subscriber = $this->userFactory->createUser('subscriber', 'subscriber@numbernine-fakedomain.com', 'password', [$this->getReference(UserRole::class . '_subscriber')], false);

        $administrator->setFirstName($this->faker->firstName)->setLastName($this->faker->lastName);
        $editor->setFirstName($this->faker->firstName)->setLastName($this->faker->lastName);
        $author->setFirstName($this->faker->firstName)->setLastName($this->faker->lastName);
        $contributor->setFirstName($this->faker->firstName)->setLastName($this->faker->lastName);
        $subscriber->setFirstName($this->faker->firstName)->setLastName($this->faker->lastName);

        $this->addReference(User::class . '_administrator', $administrator);
        $this->addReference(User::class . '_editor', $editor);
        $this->addReference(User::class . '_author', $author);
        $this->addReference(User::class . '_contributor', $contributor);
        $this->addReference(User::class . '_subscriber', $subscriber);

        $this->createMany(
            User::class,
            10,
            function (User $user): void {
                $user
                    ->setUsername($this->faker->userName)
                    ->setFirstName($this->faker->firstName)
                    ->setLastName($this->faker->lastName)
                    ->setEmail($this->faker->email)
                    ->setPassword($this->passwordEncoder->encodePassword($user, $this->faker->password))
                    ->addUserRole($this->getReference(UserRole::class . '_subscriber'));
            }
        );

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['content'];
    }

    public function getDependencies(): array
    {
        return [UserRolesFixtures::class];
    }
}
