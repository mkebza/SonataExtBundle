<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Tests\Security;

use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Event\Security\UserPasswordChangedEvent;
use MKebza\SonataExt\Security\UserChangePassword;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserChangePasswordTest extends TestCase
{
    public function testChangePassword()
    {
        $testUser = new TestUser();

        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($testUser, 'plain_password')
            ->willReturn('encoded_password');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(UserPasswordChangedEvent::class, $this->isInstanceOf(UserPasswordChangedEvent::class));

        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('persist')
            ->with($testUser);

        $object = new UserChangePassword($encoder, $dispatcher, $em);
        $object->change($testUser, 'plain_password');

        $this->assertSame($testUser->getPassword(), 'encoded_password');
    }

    public function testChangePasswordRollback()
    {
        $testUser = new TestUser();

        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new \Exception());
        $em
            ->expects($this->once())
            ->method('rollback');

        $object = new UserChangePassword($encoder, $dispatcher, $em);

        $this->expectException(\Exception::class);
        $object->change($testUser, 'rollback');
    }
}
