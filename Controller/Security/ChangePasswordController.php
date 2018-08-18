<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 10/08/2018
 * Time: 21:39
 */
declare(strict_types=1);

namespace MKebza\SonataExt\Controller\Security;

use MKebza\SonataExt\Enum\AdminFlashMessage;
use MKebza\SonataExt\Form\Type\Security\ChangePasswordType;
use MKebza\SonataExt\Security\UserChangePassword;
use MKebza\SonataExt\Service\Security\ChangePasswordAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ChangePasswordController extends AbstractController
{
    public function __invoke(Request $request, ChangePasswordAction $changer, TranslatorInterface $t)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $changer->change($this->getUser(), $form->get('plainPassword')->getData());
            $this->addFlash(
                AdminFlashMessage::SUCCESS,
                $t->trans('action.changePassword.flashPasswordChanged', [], 'admin'));

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('@SonataExt/security/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}