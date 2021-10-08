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
use Symfony\Component\Validator\Constraints\Regex;

final class AdminSettingsPermalinksFormType extends AbstractType
{
    public function __construct(private ContentService $contentService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->contentService->getContentTypes() as $contentType) {
            $builder->add($contentType->getName(), null, [
                'required' => false,
                'label' => ucfirst((string) $contentType->getLabels()->getPluralName()),
                'constraints' => [new Regex('@^/[\d\w\-_{}/]*$$@')],
            ]);
        }

        $builder
            ->add('submit', SubmitType::class)
        ;
    }
}
