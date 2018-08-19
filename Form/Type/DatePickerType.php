<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Type\Backend;

use Sonata\CoreBundle\Form\Type\DatePickerType as ParentDatePickerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatePickerType extends ParentDatePickerType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array_merge($this->getCommonDefaults(), [
            'dp_pick_time' => false,
            'format' => 'd.M.y', // override custom format
        ]));
    }
}
