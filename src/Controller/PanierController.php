<?php

namespace App\Controller;

use App\Classes\Panier;
use App\Classes\Tools;
use App\Form\UserConfirmType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Panier $panier): Response
    {

        //$panier->addProduitPanier(5);
        return $this->render('panier/index.html.twig', [

            'panier' => $panier->getDetailPanier(),

            'total_panier' => $panier->getTotalPanier()
        ]);
    }

    /**
     * @Route("/supprimer-panier", name="delete_panier")
     */
    public function delete(Panier $panier): Response
    {
        $panier->deletePanier();

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/ajouter-panier/{id}", name="add_panier")
     */
    public function addPanier($id, Panier $panier): Response
    {
        $panier->addProduitPanier($id);
        $this->addFlash('success', 'Votre article a bien était ajouté a votre panier ');

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/soustraire-quantity-panier/{id}", name="delete_quantity_produit")
     */
    public function deleteQuantityProduit($id, Panier $panier): Response
    {
        $panier->deleteQuantityProduit($id);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/supprimer-produit-panier/{id}", name="delete_produit_panier")
     */
    public function deleteProduitPanier($id, Panier $panier): Response
    {
        $panier->deleteProduitPanier($id);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/confirmer-panier", name="confirm_panier")
     */
    public function confirmePanier(Request $request, EntityManagerInterface $manager,Panier $panier,
                                    AdresseRepository $adresseRepository)
    {
        // je récupére le user connecté 
        $user = $this->getUser();
        // si noutre client n'a pas renseigné un nom ou une adresse 
        // on le renvoie vers un formulaire pour compléter les données 
        if (!$user->getNom() || !$user->getAdresse()) {
            $form = $this->createForm(UserConfirmType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('confirm_panier');
            }

            return $this->render('panier/confirme_panier.html.twig', [
                'form' => $form->createView()
            ]);
        }
        //si non on l'envoie sur la page recap 
        // je recupe l'adresse de livraison 
        
        $adresse_livr = $adresseRepository->findOneBy(['user' => $user , 'status' => true]);
        return $this->render('panier/recap_panier.html.twig', 
        [
             'panier' =>$panier->getDetailPanier(),
             'total_panier' => $panier->getTotalPanier(),
             'adresse_livr' =>$adresse_livr

        ]);
    }
      
     /**
     * @Route("/changer-adresse-recep/{id}", name="change_adresse_recap")
     */
    public function modifierAdresseRecap($id, AdresseRepository $adresseRepository,
                                            Tools $tools,EntityManagerInterface $manager): Response
    {
        $user =$this->getUser();
        $tools->setFalseAllAdresse();
        $adresse = $adresseRepository ->findOneBy(['user' => $user , 'id' => $id]);
        $adresse->setStatus(true);
        $manager->persist($adresse);
        $manager->flush();

        return $this->redirectToRoute('confirm_panier');
    }

}
