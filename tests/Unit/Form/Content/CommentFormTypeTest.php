<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Tests\Unit\Form\Content;

use NumberNine\Entity\Comment;
use NumberNine\Entity\Post;
use NumberNine\Form\Content\CommentFormType;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Tests\FormTestCase;

/**
 * @internal
 * @covers \CommentFormType
 */
final class CommentFormTypeTest extends FormTestCase
{
    public function testGuestUserSubmitValidData(): void
    {
        $post = $this->getPost();

        $formData = [
            'guestAuthorName' => 'Guest',
            'guestAuthorEmail' => 'guest@numberninecms.com',
            'guestAuthorUrl' => 'https://numberninecms.com',
            'content' => 'This a a sample comment.',
            'contentEntity' => $post->getId(),
            '_token' => $this->csrfTokenManager->getToken('comment_form')->getValue(),
        ];

        $model = new Comment();

        $form = $this->factory->create(CommentFormType::class, $model);

        $expected = (new Comment())
            ->setGuestAuthorName('Guest')
            ->setGuestAuthorEmail('guest@numberninecms.com')
            ->setGuestAuthorUrl('https://numberninecms.com')
            ->setContent('This a a sample comment.')
            ->setContentEntity($post)
        ;

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertEquals($expected, $model);
        static::assertCount(0, iterator_to_array($form->getErrors(true), false));
    }

    public function testGuestUserSubmitInvalidData(): void
    {
        $formData = [
            'guestAuthorName' => '',
            'guestAuthorEmail' => 'invalid@email',
            'guestAuthorUrl' => '',
            'content' => '',
            'contentEntity' => null,
            '_token' => $this->csrfTokenManager->getToken('comment_form')->getValue(),
        ];

        $model = new Comment();

        $form = $this->factory->create(CommentFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form['content']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['guestAuthorName']->getErrors(true)));
        static::assertGreaterThan(0, \count($form['guestAuthorEmail']->getErrors(true)));
        static::assertGreaterThan(0, \count($form->getErrors()));
    }

    public function testLoggedUserSubmitValidData(): void
    {
        $post = $this->getPost();
        $this->loginAs($post->getAuthor());

        $formData = [
            'author' => $post->getAuthor()->getId(),
            'content' => 'This a a sample comment.',
            'contentEntity' => $post->getId(),
            '_token' => $this->csrfTokenManager->getToken('comment_form')->getValue(),
        ];

        $model = new Comment();

        $form = $this->factory->create(CommentFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertCount(0, $form->getErrors(true));
    }

    public function testLoggedUserSubmitInvalidData(): void
    {
        $post = $this->getPost();
        $this->loginAs($post->getAuthor());

        $formData = [
            'author' => null,
            'content' => 'This a a sample comment.',
            'contentEntity' => $post->getId(),
            '_token' => $this->csrfTokenManager->getToken('comment_form')->getValue(),
        ];

        $model = new Comment();

        $form = $this->factory->create(CommentFormType::class, $model);

        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertGreaterThan(0, \count($form->getErrors()));
    }

    private function getPost(): Post
    {
        $author = $this->createUser('Contributor');

        $post = (new Post())
            ->setTitle('My blog post')
            ->setCustomType('post')
            ->setAuthor($author)
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('2013/12/18'))
            ->setPublishedAt(new \DateTime('2015/05/13'))
        ;

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }
}
