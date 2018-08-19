<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace MKebza\SonataExt\Controller\Security;

use MKebza\SonataExt\Enum\AdminFlashMessage;
use MKebza\SonataExt\Form\Type\Security\ResetPasswordType;
use MKebza\SonataExt\Service\Security\ResetPasswordAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordController extends AbstractController
{
    public function __invoke(string $token, Request $request, ResetPasswordAction $resetter, TranslatorInterface $translator): Response
    {
        $tokenObject = $resetter->getToken($token);
        if (null === $tokenObject) {
            $this->addFlash(AdminFlashMessage::ERROR,
               $translator->trans('action.resetPassword.flashInvalidToken', [], 'admin')
            );

            return $this->redirectToRoute('admin_reset_password_request');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resetter->reset($tokenObject, $form->get('password')->getData());
            $this->addFlash(AdminFlashMessage::SUCCESS,
                $translator->trans('action.resetPassword.flashPasswordReset', [], 'admin')
            );

            return $this->redirectToRoute('admin_login');
        }

        return $this->render('@SonataExt/security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
