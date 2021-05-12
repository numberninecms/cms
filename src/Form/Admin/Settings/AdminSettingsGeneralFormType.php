<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\Admin\Settings;

use NumberNine\Content\ContentService;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\Pagination\PaginationParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminSettingsGeneralFormType extends AbstractType
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('tagline')
            ->add('blog_homepage', CheckboxType::class, ['label' => 'Use posts archive as homepage'])
            ->add('page_for_front', ChoiceType::class, [
                'choices' => $this->getPages()
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    private function getPages(): array
    {
        $entities = $this->contentService->getEntitiesOfType(
            'page',
            (new PaginationParameters())
                ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
                ->setOrderBy('title')
                ->setOrder('asc')
        );

        $pages = [];

        foreach ($entities as $entity) {
            $pages[$entity->getTitle()] = $entity->getId();
        }

        return $pages;
    }
}
