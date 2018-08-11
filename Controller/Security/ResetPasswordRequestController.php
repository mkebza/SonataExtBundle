<?php
/**
 * Created by PhpStorm.
 * User: mkebza
 * Date: 11/08/2018
 * Time: 14:00
 */

namespace MKebza\SonataExt\Controller\Security;


use MKebza\SonataExt\Form\Type\Security\ResetPasswordRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordRequestController extends AbstractController
{
    public function __invoke(Request $request)
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('@SonataExt/security/reset_password_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}