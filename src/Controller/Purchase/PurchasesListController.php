<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasesListController extends AbstractController
{
    #[\Symfony\Component\Security\Http\Attribute\IsGranted('ROLE_USER', message: 'vous devez être connecté pour acceder à vos commandes')]
    #[Route(path: '/purchases', name: 'purchase_index')]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases(),
        ]);
    }
}
