<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
        $user = $this->security->getUser();

        if (!$user) {
         throw new AccessDeniedException("vous devez être connecté pour acceder à vos commandes ");
        }

        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}
