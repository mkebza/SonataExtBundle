<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 14:00
 */

namespace MKebza\SonataExt\Controller\Security;


use Doctrine\ORM\EntityManagerInterface;
use MKebza\SonataExt\Enum\AdminFlashMessage;
use MKebza\SonataExt\Form\Type\Security\ResetPasswordRequestType;
use MKebza\SonataExt\Security\UserResetPasswordRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordRequestController extends AbstractController
{
    public function __invoke(
        Request $request,
        UserResetPasswordRequest $resetRequest,
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        string $userEntityName
    )
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository($userEntityName)->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            $resetRequest->request($user, \DateInterval::createFromDateString('+4 hours'));
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