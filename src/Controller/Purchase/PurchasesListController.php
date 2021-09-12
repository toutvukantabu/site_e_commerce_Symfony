<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PurchasesListController extends AbstractController
{

    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $routeur, Environment $twig)
    {
        $this->security = $security;
        $this->routeur = $routeur;
        $this->twig = $twig;
    }


    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        if (!$user) {
            $url = $this->routeur->generate('homepage');
            dump($url);

            return new RedirectResponse($url);
        }

        $html = $this->twig->render('Purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}
