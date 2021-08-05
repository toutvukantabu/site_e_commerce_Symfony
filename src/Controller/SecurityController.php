<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function index(AuthenticationUtils $utils): Response
    {

        $form= $this->createForm(LoginType::class);
        return $this->render('security/login.html.twig', [
            'formview'=>$form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }
}
