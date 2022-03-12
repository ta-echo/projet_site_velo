<?php

namespace App\Controller\Admin;

use App\Entity\CodeTva;
use App\Form\CodeTvaType;
use App\Repository\CodeTvaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/code/tva")
 */
class CodeTvaController extends AbstractController
{
    /**
     * @Route("/", name="code_tva_index", methods={"GET"})
     */
    public function index(CodeTvaRepository $codeTvaRepository): Response
    {
        return $this->render('code_tva/index.html.twig', [
            'code_tvas' => $codeTvaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="code_tva_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $codeTva = new CodeTva();
        $form = $this->createForm(CodeTvaType::class, $codeTva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($codeTva);
            $entityManager->flush();

            return $this->redirectToRoute('code_tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('code_tva/new.html.twig', [
            'code_tva' => $codeTva,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="code_tva_show", methods={"GET"})
     */
    public function show(CodeTva $codeTva): Response
    {
        return $this->render('code_tva/show.html.twig', [
            'code_tva' => $codeTva,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="code_tva_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CodeTva $codeTva, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CodeTvaType::class, $codeTva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('code_tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('code_tva/edit.html.twig', [
            'code_tva' => $codeTva,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="code_tva_delete", methods={"POST"})
     */
    public function delete(Request $request, CodeTva $codeTva, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$codeTva->getId(), $request->request->get('_token'))) {
            $entityManager->remove($codeTva);
            $entityManager->flush();
        }

        return $this->redirectToRoute('code_tva_index', [], Response::HTTP_SEE_OTHER);
    }
}
