<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Controller\Security;

use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Enum\AdminFlashMessage;
use MKebza\SonataExt\Form\Type\Security\ResetPasswordRequestType;
use MKebza\SonataExt\Service\Security\ResetPasswordRequestAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordRequestController extends AbstractController
{
    protected const PASSWORD_EXPIRE_IN = '+4 hours';

    public function __invoke(
        Request $request,
        ResetPasswordRequestAction $requestMaker,
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        string $userEntityName
    ) {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository($userEntityName)->findOneBy([
                'email' => $form->get('email')->getData(),
            ]);

            if (null !== $user) {
                $requestMaker->create($user, \DateInterval::createFromDateString(self::PASSWORD_EXPIRE_IN));
            }

            $this->addFlash(
                AdminFlashMessage::SUCCESS,
                $translator->trans('action.resetPasswordRequest.flashResetPasswordRequested', [], 'admin')
            );

            return $this->redirectToRoute('admin_login');
        }

        return $this->render('@SonataExt/security/reset_password_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
