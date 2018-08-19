<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Type\Backend;

use Sonata\CoreBundle\Form\Type\DateTimePickerType as ParentDateTimePickerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends ParentDateTimePickerType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array_merge($this->getCommonDefaults(), [
            'dp_side_by_side' => true,
            'dp_pick_time' => true,
            'format' => 'd.M.y H:mm', // override custom format
        ]));
    }
}
