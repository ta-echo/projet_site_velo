<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/liste-commandes/{param}", name="commande_index", defaults={ "param" : null} ,methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository, $param ): Response
    {
       switch ($param){

            case 'livre':
                $commande = $commandeRepository->findBy(['status' => true , 'archive' => False]);
                break;
            case 'nlivre':
                $commande = $commandeRepository->findBy(['status' => false ,'archive' => False]);
                break;
            case 'tout':
                $commande = $commandeRepository->findAll();
                break;
            default:
                $commande = $commandeRepository->findBy(['status' => false ,'archive' => False]);
                break;
        

       }
        return $this->render('commande/index.html.twig', [
            'commandes' => $commande
        ]);
    }

    /**
     * @Route("/new/{id}", name="commande_new", defaults={ "id" : null } , methods={"GET", "POST"})
     */
    public function new($id,Request $request, EntityManagerInterface $entityManager,CommandeRepository $commandeRepository): Response
    {
        if($id){
           $commande = $commandeRepository->find($id);
        }else{
            $commande = new Commande();
        }
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
    }


     /**
     * @Route("/livraison/{id}", name="commande_livree", methods={"GET"})
     */
    public function livraison(Commande $commande , 
                       EntityManagerInterface $manager): Response
    {

        $commande->setStatus(true);
        $manager->persist($commande);
        $manager->flush();
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    
}
