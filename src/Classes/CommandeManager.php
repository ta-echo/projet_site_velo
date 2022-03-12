<?php
namespace App\Classes;

use DateTime;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class CommandeManager
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

   
    /**
     * Undocumented function
     *
     * @return User
     */
    public function getUserConnecte()
    {
        $user = $this->security->getUser();
        return $user;

    }


    /**
    * méthode qui créeun object de la class commande par rapport au donnée dans le paier et la personne connécté 
    * @return Commande
    */
     public function getCommande(Panier $panier)
    {
         // je crée un new object commande 
         $commande = new Commande();
         // je récup le user connécté
         $user = $this->getUserConnecte();
         // je set objet commande
         $commande->setUser($user);
         // je récup la date du jour
         $date_commande = new DateTime();
         // je set objet commande avec la dtae du jour
         $commande->setDateCommande($date_commande);
         // je crée le nom complet du user
         $nom = $user->getNom() . ' ' . $user->getprenom();
         $commande->setNom($nom);
         //je construit l'adresse en string
         $adresse = $user->getAdresse() . ' ' . $user->getVille() . " " . $user->getPays();
         $commande->setAdresseLivraison($adresse);
         $commande->setStatus(false);
         $commande->setArchive(false);
         // recuépéer le total du panier 
         $commande->setTotal($panier->getTotalPanier());

         return $commande;

     }

     public function getDetailCommande(Commande $commande , $row_panier)
     {

          // je crée un object detail_commande
          $detail= new DetailCommande();

          $detail->setCommande($commande);
          // je set le nom du produit on le récupérant de la ligne du panier 
          $detail->setName($row_panier['produit']->getName());
          //$detail->setRef($ref);
          $detail->setPrixUnit($row_panier['produit']->getPrix());
          $detail->setQuantity($row_panier['quantity']);
          $detail->setTotal($row_panier['total']);

          return $detail;

     }




}


