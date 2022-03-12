<?php

namespace App\Controller\Admin;

use App\Entity\Carrousel;
use App\Form\CarrouselType;
use App\Repository\CarrouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/carrousel")
 */
class CarrouselController extends AbstractController
{
    /**
     * @Route("/", name="carrousel_index", methods={"GET"})
     */
    public function index(CarrouselRepository $carrouselRepository): Response
    {
        return $this->render('carrousel/index.html.twig', [
            'carrousels' => $carrouselRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="carrousel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carrousel = new Carrousel();
        $form = $this->createForm(CarrouselType::class, $carrousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // je récupére le fichies passé dans le form
             $image = $form->get('name')->getdata();
             
             // si il y a une image de chargée
             if ($image) {
                 // je crée un nom unique pour cette image et je remet l'extension
                 $img_file_name = uniqid() . '.' . $image->guessExtension();
                 // enregistrer le fichier dans le dossier image 
                 $image->move($this->getParameter('upload_dir'), $img_file_name);
                 // je set l'object article
                 $carrousel->setName($img_file_name);
             } else {
                 // si $image = null je set l'image par default
                 $carrousel->setName('defaultimg.jpg');
             }
            // ici code insersion d'image
            $entityManager->persist($carrousel);
            $entityManager->flush();

            return $this->redirectToRoute('carrousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carrousel/new.html.twig', [
            'carrousel' => $carrousel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="carrousel_show", methods={"GET"})
     */
    public function show(Carrousel $carrousel): Response
    {
        return $this->render('carrousel/show.html.twig', [
            'carrousel' => $carrousel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="carrousel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Carrousel $carrousel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarrouselType::class, $carrousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('carrousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carrousel/edit.html.twig', [
            'carrousel' => $carrousel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="carrousel_delete", methods={"POST"})
     */
    public function delete(Request $request, Carrousel $carrousel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrousel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($carrousel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('carrousel_index', [], Response::HTTP_SEE_OTHER);
    }
}
