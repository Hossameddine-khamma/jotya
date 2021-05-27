<?php

namespace App\Controller;

use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Entity\Styles;
use App\Entity\Type;
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
    public function initStyles()
    {
        $stylesRepository =$this->getDoctrine()->getManager()->getRepository(Styles::class);
        $styles=$stylesRepository->findAll();
        
        return $styles;
    }

    public function initTypes()
    {
        $TypeRepository =$this->getDoctrine()->getManager()->getRepository(Type::class);
        $Types=$TypeRepository->findAll();

        
        return $Types;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $this->initStyles();
        $this->initTypes();

        $ensembles = $this->ensemblesRepository->findAll();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/style/{id}", name="styleDetails")
     */
    public function Styles(Ensembles $ensemble)
    {

        $produits= $ensemble->getProduits();

        $ensembleId=$ensemble->getId();

        $ensembles= $this->ensemblesRepository->findSimilarTo($ensemble);
       
        $chekedEnsembles= $this->checkEnsemble($ensembles);
        return $this->render('default/styleDetails.html.twig', [
            'title' => 'style',
            'produits'=>$produits,
            'route'=>'productDiscription',
            'ensembles'=>$chekedEnsembles,
            'ensembleId'=> $ensembleId
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
        $ensembles = $this->ensemblesRepository->getBonPlan();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/nouveau", name="nouveau")
     */
    public function nouveau()
    {
        $ensembles = $this->ensemblesRepository->getNouveau();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/homme", name="homme")
     */
    public function indexHomme(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("HOMME",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        return $this->render('default/index.html.twig', [
            'title' => 'Homme',
            'ensembles' => $chekedEnsembles,
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

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    
     /**
     * @Route("/homme/produits/{id}", name="hommeProduits")
     */
    public function hommeProduits(Type $Type,GenreRepository $genreRepository)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('homme',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '. $Type->getDescription(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }


     /**
     * @Route("/femme", name="femme")
     */
    public function indexFemme(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("Femme",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        
        return $this->render('default/index.html.twig', [
            'title' => 'Femme',
            'ensembles' => $chekedEnsembles,
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

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        return $this->render('default/index.html.twig', [
            'title' => 'Femme'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }

    /**
     * @Route("/femme/produits/{id}", name="femmeProduits")
     */
    public function femmeProduits(Type $Type,GenreRepository $genreRepository)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('femme',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '. $Type->getDescription(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/enfants", name="enfants")
     */
    public function indexEnfants(GenreRepository $genreRepository)
    {
        $ensembles = $this->ensemblesRepository->getGender("enfants",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants',
            'ensembles' => $chekedEnsembles,
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

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }

    /**
     * @Route("/enfants/produits/{id}", name="enfantsProduits")
     */
    public function enfantsProduits(Type $Type,GenreRepository $genreRepository)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('enfants',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '. $Type->getDescription(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }

    private function checkEnsemble($ensembles){
        foreach($ensembles as $ensemble){
            if(count($ensemble->getProduits())<1){
                $this->entityManager->remove($ensemble);
                $this->entityManager->flush($ensemble);
            }
        }
        return $ensembles;
    }

}
