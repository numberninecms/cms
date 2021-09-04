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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminSettingsEmailsFormType extends AbstractType
{
    public function __construct(private ContentService $contentService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mailer_sender_name', null, ['label' => 'Sender name'])
            ->add('mailer_sender_address', EmailType::class, ['label' => 'Sender email address'])
            ->add('submit', SubmitType::class)
        ;
    }
}
