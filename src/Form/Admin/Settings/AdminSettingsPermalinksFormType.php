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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminSettingsPermalinksFormType extends AbstractType
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->contentService->getContentTypes() as $contentType) {
            $builder->add($contentType->getName(), null, [
                'required' => false,
                'label' => ucfirst((string)$contentType->getLabels()->getPluralName()),
            ]);
        }

        $builder
            ->add('submit', SubmitType::class)
        ;
    }
}
