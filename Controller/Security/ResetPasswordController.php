<?php

declare(strict_types=1);

namespace MKebza\SonataExt\Controller\Security;

use MKebza\SonataExt\Form\Type\Security\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends AbstractController
{
    public function __invoke(string $token): Response
    {
        $form = $this->createForm(ResetPasswordType::class);

        return $this->render('@SonataExt/security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}