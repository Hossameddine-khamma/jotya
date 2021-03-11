<?php

namespace App\Controller;

use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Entity\Styles;
use App\Repository\EnsemblesRepository;
use App\Repository\GenreRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StylesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Loader\LoaderInterface;

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

    /**
     * @var ProduitsRepository
     */
    private $produitsRepository;
    
    public function __construct(EnsemblesRepository $ensemblesRepository,EntityManagerInterface $entityManager
    ,ProduitsRepository $produitsRepository)
    {
        $this->ensemblesRepository = $ensemblesRepository;
        $this->entityManager = $entityManager;
        $this->produitsRepository = $produitsRepository;
    }
    public function init()
    {
        $stylesRepository =$this->getDoctrine()->getManager()->getRepository(Styles::class);
        $styles=$stylesRepository->findAll();
        
        return $styles;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $this->init();
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
            'title' => 'style',
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
        $produits =$this->produitsRepository->findSimilarTo($produit);
        return $this->render('default/productdiscription.html.twig', [
            'title' => 'Homme produit',
            'produit'=> $produit,
            'produits'=> $produits
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
    public function indexHomme(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("HOMME",$genreRepository);
        return $this->render('default/index.html.twig', [
            'title' => 'Homme',
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
     /**
     * @Route("/homme/{id}", name="hommeStyle")
     */
    public function hommeStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('homme',$styleId,$genreRepository,$stylesRepository);
        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '.$style->getNom(),
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
     /**
     * @Route("/femme", name="femme")
     */
    public function indexFemme(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("Femme",$genreRepository);
        
        return $this->render('default/index.html.twig', [
            'title' => 'Femme',
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/femme/{id}", name="femmeStyle")
     */
    public function femmeStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('femme',$styleId,$genreRepository,$stylesRepository);
        return $this->render('default/index.html.twig', [
            'title' => 'Femme'.' '.$style->getNom(),
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/enfants", name="enfants")
     */
    public function indexEnfants(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("enfants",$genreRepository);
        
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants',
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }
   /**
     * @Route("/enfants/{id}", name="enfantsStyle")
     */
    public function enfantsStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('enfants',$styleId,$genreRepository,$stylesRepository);
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants'.' '.$style->getNom(),
            'ensembles' => $ensembles,
            'route'=>'styleDetails',
        ]);
    }

}
