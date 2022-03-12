<?php
namespace App\Classes;

use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class Tools
{

  private $manager;
  private $security;
  private $adresses;

  public function __construct(EntityManagerInterface $manager,AdresseRepository $adresseRepository,Security $security )
  {
      $this->manager = $manager;
      $this->security = $security;
      $this->adresses = $adresseRepository;
  }

  public function setFalseAllAdresse(){
    $user = $this->security->getUser();
    $adresses = $this->adresses-> findBy(['user' => $user]);

    foreach($adresses as $adresse){
       if(!is_null($adresse)){
        $adresse->setStatus(false);
        $this->manager->persist($adresse);
        
       }
       

    }
    $this->manager->flush();

  }

}