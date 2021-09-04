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

namespace NumberNine\Form\Type;

use Doctrine\ORM\EntityNotFoundException;
use NumberNine\Form\DataTransformer\ContentEntityToNumberTransformer;
use NumberNine\Repository\MediaFileRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class MediaFileType extends AbstractType
{
    public function __construct(private MediaFileRepository $mediaFileRepository, private ContentEntityToNumberTransformer $contentEntityToNumberTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->contentEntityToNumberTransformer);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        try {
            if (($id = $form->getData()) && ($mediaFile = $this->mediaFileRepository->findOneBy(['id' => $id]))) {
                $view->vars['media_file'] = $mediaFile;
            }
        } catch (EntityNotFoundException) {
            // Don't set variable if entity doesn't exist anymore
        }
    }

    public function getParent(): string
    {
        return HiddenType::class;
    }
}
