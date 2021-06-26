<?php

namespace App\Controller;

use App\Entity\Banniere;
use App\Entity\Budget;
use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Entity\Saisons;
use App\Entity\Styles;
use App\Entity\Taille;
use App\Entity\Type;
use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
    public function initBanniere()
    {
        $BanniereRepository =$this->getDoctrine()->getManager()->getRepository(Banniere::class);
        $Banniere=$BanniereRepository->findOneBy(['id'=>1]);
        
        return $Banniere;
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
    public function initTailles()
    {
        $tailleRepository =$this->getDoctrine()->getManager()->getRepository(Taille::class);
        $tailles=$tailleRepository->findAll();

        
        return $tailles;
    }

    public function initSaisons()
    {
        $SaisonsRepository =$this->getDoctrine()->getManager()->getRepository(Saisons::class);
        $Saisons=$SaisonsRepository->findAll();

        
        return $Saisons;
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $utilisateur=new Utilisateurs();

        $form=$this->createForm(UtilisateursType::class,$utilisateur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pass=$passwordEncoder->encodePassword($utilisateur,$utilisateur->getPassword());
            $utilisateur->setPassword($pass);

            $manager=$this->getDoctrine()->getManager();
            $manager->persist($utilisateur);
            $manager->flush($utilisateur);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('utilisateurs/nouveau.html.twig', [
            'controller_name' => 'UtilisateursController',
            'form'=>$form->createView()
        ]);
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
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }
            

            return $this->render('default/index.html.twig', [
                'title' => 'Accueil',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
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
    public function Styles(Ensembles $ensemble,Request $request)
    {

        $produits= $ensemble->getProduits();

        $ensembleId=$ensemble->getId();

        $ensembles= $this->ensemblesRepository->findSimilarTo($ensemble);
       
        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);

            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }
            

            return $this->render('default/styleDetails.html.twig', [
                'title' => 'style',
                'produits'=>$produits,
                'route'=>'productDiscription',
                'ensembles'=>$newEnsembles,
                'ensembleId'=> $ensembleId
            ]);
        }  
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
    public function products(Produits $produit,Request $request)
    {
        $produits =$this->produitsRepository->findSimilarTo($produit);
        $newProduits =$produits;

        if($request->isMethod('POST')){
            $form=$request->request;
            $newProduits=$this->filtrerProduits($form,$produits);
            
            if($newProduits=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newProduits=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/productdiscription.html.twig', [
                'title' => 'produit',
                'produit'=> $produit,
                'produits'=> $newProduits
            ]);
        }
        return $this->render('default/productdiscription.html.twig', [
            'title' => 'produit',
            'produit'=> $produit,
            'produits'=> $produits
        ]);
    }
    /**
     * @Route("/plans", name="bonPlans")
     */
    public function plans(Request $request)
    {
        $ensembles = $this->ensemblesRepository->getBonPlan();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }
            return $this->render('default/index.html.twig', [
                'title' => 'Accueil',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }

        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/nouveau", name="nouveau")
     */
    public function nouveau(Request $request)
    {
        $ensembles = $this->ensemblesRepository->getNouveau();

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }
            return $this->render('default/index.html.twig', [
                'title' => 'Accueil',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }

        return $this->render('default/index.html.twig', [
            'title' => 'Accueil',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/homme", name="homme")
     */
    public function indexHomme(GenreRepository $genreRepository,Request $request)
    {
        $ensembles = $this->ensemblesRepository->getGender("HOMME",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }
            return $this->render('default/index.html.twig', [
                'title' => 'Homme',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        return $this->render('default/index.html.twig', [
            'title' => 'Homme',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
     /**
     * @Route("/homme/{id}", name="hommeStyle")
     */
    public function hommeStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository,Request $request)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('homme',$styleId,$genreRepository,$stylesRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Homme'.' '.$style->getNom(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    
     /**
     * @Route("/homme/produits/{id}", name="hommeProduits")
     */
    public function hommeProduits(Type $Type,GenreRepository $genreRepository,Request $request)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('homme',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Homme'.' '. $Type->getDescription(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }


        return $this->render('default/index.html.twig', [
            'title' => 'Homme'.' '. $Type->getDescription(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }


     /**
     * @Route("/femme", name="femme")
     */
    public function indexFemme(GenreRepository $genreRepository,Request $request)
    {
        $ensembles = $this->ensemblesRepository->getGender("Femme",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Femme',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        
        return $this->render('default/index.html.twig', [
            'title' => 'Femme',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/femme/{id}", name="femmeStyle")
     */
    public function femmeStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository, Request $request)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('femme',$styleId,$genreRepository,$stylesRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Femme'.' '.$style->getNom(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        return $this->render('default/index.html.twig', [
            'title' => 'Femme'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }

    /**
     * @Route("/femme/produits/{id}", name="femmeProduits")
     */
    public function femmeProduits(Type $Type,GenreRepository $genreRepository,Request $request)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('femme',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        $chekedEnsembles= $this->checkEnsemble($ensembles);
        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Femme'.' '. $Type->getDescription(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }

        return $this->render('default/index.html.twig', [
            'title' => 'Femme'.' '. $Type->getDescription(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
    /**
     * @Route("/enfants", name="enfants")
     */
    public function indexEnfants(GenreRepository $genreRepository,Request $request)
    {
        $ensembles = $this->ensemblesRepository->getGender("enfants",$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Enfants',
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants',
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }
   /**
     * @Route("/enfants/{id}", name="enfantsStyle")
     */
    public function enfantsStyles(Styles $style,GenreRepository $genreRepository, StylesRepository $stylesRepository,Request $request)
    {
        $styleId = $style->getId();
        $ensembles = $this->ensemblesRepository->getStyleGender('enfants',$styleId,$genreRepository,$stylesRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Enfants'.' '.$style->getNom(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }
        return $this->render('default/index.html.twig', [
            'title' => 'Enfants'.' '.$style->getNom(),
            'ensembles' => $chekedEnsembles,
            'route'=>'styleDetails',
        ]);
    }

    /**
     * @Route("/enfants/produits/{id}", name="enfantsProduits")
     */
    public function enfantsProduits(Type $Type,GenreRepository $genreRepository,Request $request)
    {
        $TypeId = $Type->getId();
        $ensembles = $this->ensemblesRepository->getproduitsGender('enfants',$TypeId,$genreRepository);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        $chekedEnsembles= $this->checkEnsemble($ensembles);

        if($request->isMethod('POST')){
            $form=$request->request;
            $newEnsembles=$this->filtrer($form,$chekedEnsembles);
            
            if($newEnsembles=="pas de favoris"){
                return $this->redirectToRoute('compte');
            }
            if($newEnsembles=="utilisateur non connecté"){
                return $this->redirectToRoute('app_login');
            }

            return $this->render('default/index.html.twig', [
                'title' => 'Enfants'.' '. $Type->getDescription(),
                'ensembles' => $newEnsembles,
                'route'=>'styleDetails',
            ]);
        }

        return $this->render('default/index.html.twig', [
            'title' => 'Enfants'.' '. $Type->getDescription(),
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

    private function filtrer(ParameterBag $form,array $ensembles){
        $newEnsembles=$ensembles;
            //montrer
            if($form->get("montrer")){
                $newEnsembles=$this->filtreMontrer($form->get("montrer"),$ensembles);
                if($newEnsembles=="pas de favoris"){
                    return $newEnsembles;
                }
                if($newEnsembles=="utilisateur non connecté"){
                    return $newEnsembles;
                }
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
            return $newEnsembles;

        
    }

    private function filtrerProduits(ParameterBag $form,array $produits){
        $newProduits=$produits;
            //montrer
            if($form->get("montrer")){
                $newProduits=$this->filtreMontrerProduit($form->get("montrer"),$produits);
            }
            //budget
            if($form->get("budget")){
                dump($newProduits,$form->get("budget"));
                $newProduits=$this->filtreBudgetProduit($form->get("budget"),$newProduits);
            }
            //taille 
            if($form->get("taille")){
                $newProduits=$this->filtreTailleProduit($form->get("taille"),$form->get("pointure"),$newProduits);
            }
            //pointure
            if($form->get("pointure") && !$form->get("taille")){
                $newProduits=$this->filtrePointureProduit($form->get("pointure"),$newProduits);
            }
            //saison
            if($form->get("Saison")){
                $newProduits=$this->filtreSaisonProduit($form->get("Saison"),$newProduits);
            }
            return $newProduits;

        
    }

    private function filtreMontrerProduit(string $critére, array $produits){
        if($critére === "TENUES POPULAIRES"){
            //pour chaque produit il faut compter c'est lover et les classé par order desc
            $mostLikedProductIds=array_keys ($this->produitsRepository->getMostLiked()); 
            $Tabproduit=array();
            foreach($produits as $produit){
                if(in_array($produit->getId(), $mostLikedProductIds)){
                    array_push($Tabproduit,$produit);
                }
            }
            return $Tabproduit;
        }
        if($critére === "TOUTES LES TENUES"){
            return $produits;
        }
        if($critére === "STYLES SUIVIS"){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user!="anon."){
                $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
                if($user->getFavorisUtilisateurs()){
                    $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
                    if($style){
                        $Tabproduit=array();
                        
                        foreach($produits as $produit){
                            if(in_array($style,$produit->getStyles())){
                                array_push($Tabproduit,$produit);
                            }
                        }
                        return $Tabproduit;
                    }
                    else{
                        return "pas de favoris";
                    }
                }
                else{
                    return "pas de favoris";
                }
            }
            else{
                return "utilisateur non connecté";
            }

        }if($critére === "TENUES POUR MOI"){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user!="anon."){
                $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
                if($user->getFavorisUtilisateurs()){
                    $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
                    if($style){
                        $genre=$utilisateur->getFavorisUtilisateurs()->getGenre();
                        if($genre){
                            $produitsFavStyle=array();
                            foreach($produits as $produit){
                                if(in_array($style,$produit->getStyles())){
                                    array_push($produitsFavStyle,$produit);
                                }
                            }
                            $Tabproduit=array();
                            foreach($produitsFavStyle as $produit){
                                if($genre==$produit->getGenre()){
                                    array_push($Tabproduit,$produit);
                                }
                            }
                            return $Tabproduit;
                        }
                        else{
                            return "pas de favoris";
                        }
                    }
                    else{
                        return "pas de favoris";
                    }
                }
                else{
                    return "pas de favoris";
                }
            }
            else{
                return "utilisateur non connecté";
            }
        }
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
            if($user!="anon."){
                if($user->getFavorisUtilisateurs()){
                    $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
                    $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
                    if($style){
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
                    }
                    else{
                        return "pas de favoris";
                    }
                }
                else{
                    return "pas de favoris";
                }
            }
            else{
                return "utilisateur non connecté";
            }
            
        }if($critére === "TENUES POUR MOI"){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user!="anon."){
                $utilisateur=$this->utilisateursRepository->findOneBy(["email"=>$user->getUsername()]);
                if($user->getFavorisUtilisateurs()){
                    $style=$utilisateur->getFavorisUtilisateurs()->getStyle();
                    if($style){
                        $genre=$utilisateur->getFavorisUtilisateurs()->getGenre();
                            if($genre){
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
                            else{
                                return "pas de favoris";
                            }
                    }
                    else{
                        return "pas de favoris";
                    }
                }
                else{
                    return "pas de favoris";
                }
            }
            else{
                return "utilisateur non connecté";
            }

        }
    }

    private function filtreBudgetProduit(int $critére, array $produits){
        $budget=$this->budgetRepository->findOneBy(["id"=>$critére]);
        $Tabproduit=array();
        foreach($produits as $produit){
            if($budget == $produit->getBudget()){
                array_push($Tabproduit,$produit);
            }
        }
        return $Tabproduit;
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
    private function filtreSaisonProduit(int $critére, array $produits){
        $Saison=$this->saisonsRepository->findOneBy(["id"=>$critére]);
        $Tabproduit=array();
        $produitSaisons=array();
        foreach($produits as $produit){
            foreach($produit->getSaisons() as $produitSaison)
            array_push($produitSaisons,$produitSaison);
        };
        foreach($produits as $produit){
            if(in_array($Saison,$produitSaisons)){
                array_push($Tabproduit,$produit);
            }
        }
        return $Tabproduit;
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

    private function filtreTailleProduit(string $critére, string $pointure, array $produits){
        if($critére == "XS/S"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XS"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"s"]);

            $produitsTaille=array();
            $Tabproduit=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                foreach($produits as $produit){
                    if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                        array_push($Tabproduit,$produit);
                    }
                }
            return $Tabproduit;
        }
        if($critére == "M/L"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"M"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"L"]);

            $produitsTaille=array();
            $Tabproduit=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                foreach($produits as $produit){
                    if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                        array_push($Tabproduit,$produit);
                    }
                }
            return $Tabproduit;
        }
        if($critére == "XL/XXL"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XL"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"XXL"]);

            $produitsTaille=array();
            $Tabproduit=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                foreach($produits as $produit){
                    if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                        array_push($Tabproduit,$produit);
                    }
                }
            return $Tabproduit;
        }
        if($critére == "XXL/XXXL"){
            $Taille1=$this->tailleRepository->findOneBy(["valeur"=>"XXL"]);
            $Taille2=$this->tailleRepository->findOneBy(["valeur"=>"XXXL"]);

            $produitsTaille=array();
            $Tabproduit=array();
                foreach($produits as $produit){
                    array_push($produitsTaille,$produit->getTaille());
                }
                foreach($produits as $produit){
                    if(in_array($Taille1,$produitsTaille) || in_array($Taille2,$produitsTaille) || in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                        array_push($Tabproduit,$produit);
                    }
                }
            return $Tabproduit;
        }
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
    private function filtrePointureProduit(string $pointure, array $produits){
        $Tabproduit=array();
        $produitsTaille=array();
        foreach($produits as $produit){
            array_push($produitsTaille,$produit->getTaille());
        }
        foreach($produits as $produit){
            if(in_array($this->tailleRepository->findOneBy(["valeur"=>$pointure]),$produitsTaille) ){
                array_push($Tabproduit,$produit);
            }
        }
         return $Tabproduit;
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
