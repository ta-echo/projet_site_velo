<?php

namespace App\Controller;

use App\Classes\Tools;
use DateTime;
use App\Entity\Adresse;
use App\Entity\Contact;
use App\Form\AdresseType;
use App\Form\ContactType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
         
        ]);
    }


    /**
     * @Route("/utilisateur-contact", name="user_contact_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // _________debut code_____
            // je récupére la date du jour 
            $date_jour = new DateTime()  ;
            //je set l'object contact avec la date du jour
            $contact->setDateEnvoi($date_jour);//
            // je récupére le user connécté 
            $user = $this->getUser();
            // je set mon object contact avec le user connécté
            $contact->setUser($user);
            //_________fin code _________
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new_contact.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/nouvelle-adresse-livraison", name="user_adresse_new", methods={"GET", "POST"})
     */
    public function newadress(Request $request, EntityManagerInterface $entityManager,
    AdresseRepository $adresseRepository , Tools $tools): Response
    {
        $adresse = new Adresse();
        $user=$this->getUser();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $tools->setFalseAllAdresse();
            $adresse->setStatus(true);
            $adresse->setUser($user);
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new_adresse.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
            'adresses'=>$adresseRepository->findBy(['user' => $user])
        ]);
    }

     /**
     * @Route("/profil/{id}", name="edit_adresse_livraison")
     */
    public function editAddresse($id, AdresseRepository $adresseRepository,EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $liste_adresse = $user->getadresses();
        foreach($liste_adresse as $row){

             $row->setStatus(false);
             $manager->persist($row);

        }
         $adresse = $adresseRepository->find($id);
         $adresse->setStatus(true);
         $manager->persist($adresse);
         $manager ->flush();


        return $this->redirectToRoute('profil');
    }
}
