<?php

namespace App\Classes;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private $session;
    private $produitRepository;

    public function __construct(SessionInterface $sessionInterface, ProduitRepository $produitRepository)
    {
        $this->session = $sessionInterface;
        $this->produitRepository = $produitRepository;
    }

    /**
     * Function qui ajoute un produit au panier
     * si le produit s y trouve déja , elle ajout 1 a la quantité 
     * @param Int $id
    
     */
    public function addProduitPanier($id)
    {
        // je récupére un tableau 'panier' , s'il n'existe pas , on vas créer un vide )
        $panier = $this->session->get('panier',  []);
        // je vérifie si l'article ($id) ce trouv dans le panier
        if (!empty($panier[$id])) {
            // si oui je rajoute 1 a la quantité
            $panier[$id] = $panier[$id] + 1;
        } else {
            // j'enregistre le nouveau produit avec la valeur 1 (quantité)
            $panier[$id] = 1;
        }
        // je met a jour mon tableau panier avec les nouvelle valeur 
        $this->session->set('panier', $panier);
    }

    /**
     * renvoie le panier 
     */
    public function getTableauPanier()
    {
        // récupére le tableau panier de la session , 
        // si elle ne trouve rien, elle renvoi un tableau vide 
        return $this->session->get('panier', []);
    }

    /**
     * Méthode qui efface tous le panier
     *
     * @return void
     */
    public function deletePanier()
    {

        $this->session->remove('panier');
    }


    public function deleteQuantityProduit($id)
    {
        // je récupére le panier 
        $panier = $this->getTableauPanier();
        // je vérifi que le produit ce trouve bien dans le panier 
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                // si quantité est superieure a 1 , j'enléve 1 
                $panier[$id] = $panier[$id] - 1;
            } else {
                // si non je supprime le produit 
                unset($panier[$id]);
            }
        }
        // j'enregistre les modif a    ns le panier
        $this->session->set('panier', $panier);
    }
    
    public function getDetailPanier()
    {

        // récup du panier 
        $panier = $this->getTableauPanier();
        // créer un tableau vide 
        $detail_panier = [];
        // je boucle sur le tableau panier
        foreach ($panier as $key => $quantity) {
            $produit = $this->produitRepository->find($key);

            // j'aoute l'objet produit et la quantité récupéré au tableau vide
            if ($produit) {
                $taux = $produit->getTva()->getTaux();
                $total = $quantity * $produit->getPrix();
                $ht =  $total/(1 + ($taux/100));
                $tva = $total - $ht ;
               
                
                $detail_panier[] =
                    [
                        'produit'  => $produit,
                        'quantity' => $quantity,
                        'ht' => $ht,
                        'total' => $total,
                        'tva' => $tva
                     
                    ];
            }
        }
        // je r'envoie le nouveau tableau
        return $detail_panier;
    }

    public function getTotalPanier()
    {

        // je récupére le tabelau detaiul du panier 
        $panier = $this->getDetailPanier();
        // je declare une variable totoal
        $total_panier = 0;
        foreach ($panier as $line) {

            $total_panier = $total_panier + $line['total'];
            // $totoal_panier += $line['total'];

        }


        return $total_panier;
    }


    public function deleteProduitPanier($id)
    {
        // je récupére le panier 
        $panier = $this->getTableauPanier();
        // je vérifi que le produit ce trouve bien dans le panier 
        if (!empty($panier[$id])) {

            // si non je supprime le produit 
            unset($panier[$id]);
        }
        // j'enregistre les modif a    ns le panier
        $this->session->set('panier', $panier);
    }
}
