<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\DataFixture\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use MKebza\SonataExt\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserProcessor constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Processes an object before it is persisted to DB.
     *
     * @param string $id     Fixture ID
     * @param User   $object
     */
    public function preProcess(string $id, $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        $object->setPassword($this->encoder->encodePassword($object, $object->getPassword()));
    }

    /**
     * Processes an object after it is persisted to DB.
     *
     * @param string $id     Fixture ID
     * @param object $object
     */
    public function postProcess(string $id, $object): void
    {
        // Do nothing
    }
}
