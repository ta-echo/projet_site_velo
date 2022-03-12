<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CarrouselRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $produitRepository,CarrouselRepository $carrouselRepository): Response
    {
        return $this->render('home/index.html.twig', 
        [
           'list_produit'=>$produitRepository->findAll(),
           'carrousels' =>$carrouselRepository->findAll()
        ]);
    }
    
    /**
     * @Route("/detail-produit/{id}", name="show_detail_produit", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('home/detail_produit.html.twig', [
            'produit' => $produit,
        ]);
    }

}
