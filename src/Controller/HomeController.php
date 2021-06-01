<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Entity\Saisons;
use App\Entity\Styles;
use App\Entity\Type;
use App\Repository\BudgetRepository;
use App\Repository\EnsemblesRepository;
use App\Repository\GenreRepository;
use App\Repository\ProduitsRepository;
use App\Repository\SaisonsRepository;
use App\Repository\StylesRepository;
use App\Repository\TailleRepository;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
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

     /**
     * @var UtilisateursRepository
     */
    private $utilisateursRepository;

    /**
     * @var BudgetRepository
     */
    private $budgetRepository;

    /**
     * @var SaisonsRepository
     */
    private $saisonsRepository;

    /**
     * @var TailleRepository
     */
    private $tailleRepository;
    
    public function __construct(EnsemblesRepository $ensemblesRepository,EntityManagerInterface $entityManager
    ,ProduitsRepository $produitsRepository,UtilisateursRepository $utilisateursRepository,
    BudgetRepository $budgetRepository ,SaisonsRepository $saisonsRepository,TailleRepository $tailleRepository)
    {
        $this->ensemblesRepository = $ensemblesRepository;
        $this->utilisateursRepository = $utilisateursRepository;
        $this->entityManager = $entityManager;
        $this->produitsRepository = $produitsRepository;
        $this->budgetRepository = $budgetRepository;
        $this->saisonsRepository = $saisonsRepository;
        $this->tailleRepository = $tailleRepository;
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
    public function initBudgets()
    {
        $BudgetRepository =$this->getDoctrine()->getManager()->getRepository(Budget::class);
        $Budgets=$BudgetRepository->findAll();

        
        return $Budgets;
    }
    public function initSaisons()
    {
        $SaisonsRepository =$this->getDoctrine()->getManager()->getRepository(Saisons::class);
        $Saisons=$SaisonsRepository->findAll();

        
        return $Saisons;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $this->initStyles();
        $this->initTypes();

        $ensembles = $this->ensemblesRepository->findAll();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $this->filtrer($form,$chekedEnsembles,'default/index.html.twig');
        }   

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

    private function filtrer(ParameterBag $form,array $ensembles,string $template){
        $newEnsembles=$ensembles;
            //montrer
            if($form->get("montrer")){
                $newEnsembles=$this->filtreMontrer($form->get("montrer"),$ensembles);
            }
            //budget
            if($form->get("budget")){
                $newEnsembles=$this->filtreBudget($form->get("budget"),$newEnsembles);
            }
            //taille 
            if($form->get("taille")){
                $newEnsembles=$this->filtreTaille($form->get("taille"),$form->get("pointure"),$newEnsembles);
            }
            //pointure
            if($form->get("pointure") && !$form->get("taille")){
                $newEnsembles=$this->filtrePointure($form->get("pointure"),$newEnsembles);
            }
            //saison
            if($form->get("Saison")){
                $newEnsembles=$this->filtreSaison($form->get("Saison"),$newEnsembles);
            }

            return $this->render($template, [
                'title' => 'Accueil',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        
    }
    private function filtreMontrer(string $critére, array $ensembles){
        if($critére === "TENUES POPULAIRES"){
            //pour chaque produit il faut compter c'est lover et les classé par order desc
            $mostLikedProductIds=array_keys ($this->produitsRepository->getMostLiked($ensembles)); 
            //chercher les ensembles qui contient c'est produits
            
            $ensembleWithPopulaireProduct= array();
            
            foreach($ensembles as $ensemble){
                $idProducts= array();
                $ensembleProuducts=$ensemble->getProduits();
                foreach($ensembleProuducts as $ensembleProuduct){
                    array_push($idProducts,$ensembleProuduct->getId());
                }
                $i = 0;
                foreach($mostLikedProductIds as $LikedProductId){
                    if(in_array($LikedProductId,$idProducts)){
                        $i= $i+1;
                    }
                }
                    $ensembleWithPopulaireProduct[]=["fav"=>$i,"ensemble"=>$ensemble];
            }
            //trier les ensembles 
            array_multisort($ensembleWithPopulaireProduct,SORT_DESC);

            $Tabensemble=array();
            foreach($ensembleWithPopulaireProduct as $en){
                if($en["fav"]>0){
                    array_push($Tabensemble,$en["ensemble"]);
                }
            }
            return $Tabensemble;
        }
        if($critére === "TOUTES LES TENUES"){
            return $ensembles;
        }
        if($critére === "STYLES SUIVIS"){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
            $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
            $Tabensemble=array();

            foreach($ensembles as $ensemble){
                $ensembleStyles=array();
                foreach($ensemble->getStyles() as $ensembleStyle){
                    array_push($ensembleStyles,$ensembleStyle);
                };
                if(in_array($style,$ensembleStyles)){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
        }if($critére === "TENUES POUR MOI"){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
            $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
            $genre=$utilisateur->getFavorisUtilisateurs()->getGenre();
            $ensembleFavStyle=array();

            foreach($ensembles as $ensemble){
                $ensembleStyles=array();
                foreach($ensemble->getStyles() as $ensembleStyle){
                    array_push($ensembleStyles,$ensembleStyle);
                };
                if(in_array($style,$ensembleStyles)){
                    array_push($ensembleFavStyle,$ensemble);
                }
            }
            $Tabensemble=array();
            foreach($ensembleFavStyle as $ensemble){
                if($genre==$ensemble->getGenre()){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;

        }
    }

    private function filtreBudget(int $critére, array $ensembles){
        $budget=$this->budgetRepository->findOneBy(["id"=>$critére]);
        $Tabensemble=array();
        foreach($ensembles as $ensemble){
            if($budget == $ensemble->getBudget()){
                array_push($Tabensemble,$ensemble);
            }
        }
        return $Tabensemble;
    }

    private function filtreSaison(int $critére, array $ensembles){
        $Saison=$this->saisonsRepository->findOneBy(["id"=>$critére]);
        $Tabensemble=array();
        foreach($ensembles as $ensemble){
            $ensembleSaisons=array();
            foreach($ensemble->getSaisons() as $ensembleSaison){
                array_push($ensembleSaisons,$ensembleSaison);
            };
            if(in_array($Saison,$ensembleSaisons)){
                array_push($Tabensemble,$ensemble);
            }
        }
        return $Tabensemble;
    }

    private function filtreTaille(string $critére, string $pointure, array $ensembles){
        if($critére == "XS/S"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XS"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"s"]);

            $Tabensemble=array();
            foreach($ensembles as $ensemble){
                $produits=$ensemble->getProduits();
                $produitsTaille=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
        }
        if($critére == "M/L"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"M"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"L"]);

            $Tabensemble=array();
            foreach($ensembles as $ensemble){
                $produits=$ensemble->getProduits();
                $produitsTaille=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
        }
        if($critére == "XL/XXL"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XL"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"XXL"]);

            $Tabensemble=array();
            foreach($ensembles as $ensemble){
                $produits=$ensemble->getProduits();
                $produitsTaille=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
        }
        if($critére == "XXL/XXXL"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XXL"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"XXXL"]);

            $Tabensemble=array();
            foreach($ensembles as $ensemble){
                $produits=$ensemble->getProduits();
                $produitsTaille=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
        }
    }
    private function filtrePointure(string $pointure, array $ensembles){
        $Tabensemble=array();
            foreach($ensembles as $ensemble){
                $produits=$ensemble->getProduits();
                $produitsTaille=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                if(in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                    array_push($Tabensemble,$ensemble);
                }
            }
            return $Tabensemble;
    }
}
