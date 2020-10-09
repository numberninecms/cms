<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form;

use NumberNine\Annotation\Form\Control\FormControlInterface;
use NumberNine\Annotation\ExtendedReader;

final class FormGenerator
{
    private ExtendedReader $annotationReader;

    public function __construct(ExtendedReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param mixed $object
     * @return array
     */
    public function getFormControls($object): array
    {
        $annotations = $this->annotationReader->getAllAnnotations($object);

        $controls = [];

        foreach ($annotations as $field => $fieldAnnotations) {
            foreach ($fieldAnnotations as $annotation) {
                if ($annotation instanceof FormControlInterface) {
                    $controls[$field] = [
                        'name' => basename(str_replace('\\', '/', get_class($annotation))),
                        'parameters' => $annotation,
                    ];
                    break;
                }
            }
        }

        return $controls;
    }
}
