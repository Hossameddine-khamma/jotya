<?php

namespace App\Controller;

use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Entity\Styles;
use App\Repository\EnsemblesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var EnsemblesRepository
     */
    private $ensemblesRepository;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(EnsemblesRepository $ensemblesRepository,EntityManagerInterface $entityManager)
    {
        $this->ensemblesRepository = $ensemblesRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $ensembles = $this->ensemblesRepository->findAll();
        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/style/{id}", name="styleDetails")
     */
    public function Styles(Ensembles $ensemble)
    {

        $produits= $ensemble->getProduits();

        $ensembles= $this->ensemblesRepository->findSimilarTo($ensemble);
       

        return $this->render('default/styleDetails.html.twig', [
            'title' => 'Homme style',
            'produits'=>$produits,
            'route'=>'productDiscription',
            'ensembles'=>$ensembles
        ]);
    }

    /**
     * @Route("/style/product/{id}", name="productDiscription")
     */
    public function products(Produits $produit)
    {
        dump($produit);
        return $this->render('default/productdiscription.html.twig', [
            'title' => 'Homme produit',
        ]);
    }
    /**
     * @Route("/plans", name="bonPlans")
     */
    public function plans()
    {
        return $this->render('default/index.html.twig', [
            'title' => 'Bon plans',
        ]);
    }
    /**
     * @Route("/nouveau", name="nouveau")
     */
    public function nouveau()
    {
        return $this->render('default/index.html.twig', [
            'title' => 'Nouveau',
        ]);
    }
    /**
     * @Route("/homme", name="homme")
     */
    public function indexHomme()
    {
        return $this->render('default/index.html.twig', [
            'title' => 'Homme',
        ]);
    }
     /**
     * @Route("/homme/style/{id}", name="hommeStyleDetails")
     */
    public function hommeStyles()
    {
        return $this->render('default/styleDetails.html.twig', [
            'title' => 'Homme style',
        ]);
    }
    /**
     * @Route("/homme/style/product/{id}", name="hommeProductDiscription")
     */
    public function hommeproducts()
    {
        return $this->render('default/productdiscription.html.twig', [
            'title' => 'Homme produit',
        ]);
    }
     /**
     * @Route("/femme", name="femme")
     */
    public function indexFemme()
    {
        return $this->render('femme/index.html.twig', [
            'controller_name' => 'Femme',
        ]);
    }
    /**
     * @Route("/enfants", name="enfants")
     */
    public function indexEnfants()
    {
        return $this->render('enfants/index.html.twig', [
            'controller_name' => 'Enfants',
        ]);
    }
   

}
