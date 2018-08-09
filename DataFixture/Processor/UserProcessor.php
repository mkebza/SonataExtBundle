<?php
/*
 * (c) Marek Kebza <marek@kebza.cz>
 */

namespace MKebza\SonataExt\DataFixture\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserProcessor implements ProcessorInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserProcessor constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Processes an object before it is persisted to DB.
     *
     * @param string           $id     Fixture ID
     * @param \App\Entity\User $object
     */
    public function preProcess(string $id, $object): void
    {
        if (!$object instanceof \App\Entity\User) {
            return;
        }

        $object->setUsername($object->getEmail());

        $this->userManager->updateCanonicalFields($object);
        $this->userManager->updatePassword($object);
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
