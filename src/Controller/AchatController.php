<?php

namespace App\Controller;

use App\Classes\CommandeManager;
use App\Classes\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AchatController extends AbstractController
{
    /**
     * @Route("/achat", name="achat")
     */
    public function index(EntityManagerInterface $manager ,Panier $panier , CommandeManager $commandeManager): Response
    {
        // je crée un objct commande avec la méthode getcommande de ma class commandemanager
        $commande = $commandeManager->getCommande($panier);
        // je persist l'object commande
        $manager->persist($commande);
        // je crée les object detailcommande avec la méthode getdetailcommande de ma class commandemanager
        $tableau = $panier->getDetailPanier();
        foreach($tableau as $row_panier ){

          
            $detail = $commandeManager->getDetailCommande($commande ,$row_panier);

            $manager->persist($detail);

        }

        $manager->flush();
        $panier->deletePanier();
        // TODO  ici code pour carte bancaire 

        return $this->redirectToRoute('home');
    }
}
