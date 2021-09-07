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

namespace NumberNine\Form\Admin\UserRole;

use NumberNine\Entity\UserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminUserRoleIndexFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserRole $role */
        foreach ($options['roles'] as $role) {
            if (!\in_array($role->getName(), $options['built_in_roles'], true)) {
                $key = sprintf('delete_%d', $role->getId());
                $builder->add($key, SubmitType::class, ['attr' => ['value' => $key]]);
            }

            foreach ($options['capabilities'] as $capability) {
                $builder->add(sprintf('cap_%d_%s', $role->getId(), $capability), CheckboxType::class, [
                    'required' => false,
                ]);
            }
        }

        $builder->add('save', SubmitType::class, ['attr' => ['value' => 'save']]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'transformData']);
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'submitData']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['roles', 'capabilities', 'built_in_roles']);
        $resolver->setAllowedTypes('roles', 'array');
        $resolver->setAllowedTypes('capabilities', 'array');
        $resolver->setAllowedTypes('built_in_roles', 'array');
    }

    public function transformData(FormEvent $event): void
    {
        /** @var UserRole[] */
        $roles = $event->getForm()->getConfig()->getOptions()['roles'];
        $capabilities = $event->getForm()->getConfig()->getOptions()['capabilities'];
        $data = [];

        foreach ($roles as $role) {
            foreach ($capabilities as $capability) {
                $key = sprintf('cap_%d_%s', $role->getId(), $capability);
                $data[$key] = \in_array($capability, $role->getCapabilities(), true);
            }
        }

        $event->setData($data);
    }

    public function submitData(FormEvent $event): void
    {
        /** @var UserRole[] */
        $roles = $event->getForm()->getConfig()->getOptions()['roles'];
        $capabilities = $event->getForm()->getConfig()->getOptions()['capabilities'];
        $form = $event->getForm();

        foreach ($roles as $role) {
            $newCapabilities = [];

            foreach ($capabilities as $capability) {
                $key = sprintf('cap_%d_%s', $role->getId(), $capability);

                if ($form[$key]->getData()) {
                    $newCapabilities[] = $capability;
                }
            }

            $role->setCapabilities($newCapabilities);
        }
    }
}
