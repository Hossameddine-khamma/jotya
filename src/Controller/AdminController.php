<?php

namespace App\Controller;

use App\Entity\Banniere;
use App\Entity\Budget;
use App\Entity\Ensembles;
use App\Entity\Messages;
use App\Entity\Produits;
use App\Entity\Saisons;
use App\Entity\Styles;
use App\Entity\Taille;
use App\Entity\Type;
use App\Entity\Utilisateurs;
use App\Form\BanniereType;
use App\Form\BudgetsType;
use App\Form\EnsemblesType;
use App\Form\ProduitsType;
use App\Form\SaisonsType;
use App\Form\StylesType;
use App\Form\TaillesType;
use App\Form\TypesType;
use App\Repository\BanniereRepository;
use App\Repository\EnsemblesRepository;
use App\Repository\MessagesRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UtilisateursRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
* @Route("/admin")
*/
class AdminController extends AbstractController
{
   
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    /**
     * @Route("/messagerie", name="messagerie")
     */
    public function messagerie(MessagesRepository $messagesRepo,UtilisateursRepository $utilisateursRepo, Security $security): Response
    {
        $admin=$utilisateursRepo->findOneBy(['email'=>$security->getUser()->getUsername()]);
        $messages=$messagesRepo->messagesParUtilisateur($admin);
       //dd($messages);
        return $this->render('admin/messagerieBase.html.twig', [
            'controller_name' => 'AdminController',
            'messagesParUtilisateur'=>$messages
        ]);
    }
    /**
     * @Route("/messagerie/{i}", name="messagerieUtilisateur")
     */
    public function messagerieUtilisateur(Request $request,MessagesRepository $messagesRepo,UtilisateursRepository $utilisateursRepo, Security $security, $i, EntityManagerInterface $entityManager): Response
    {
        
        $admin=$utilisateursRepo->findOneBy(['email'=>$security->getUser()->getUsername()]);
        $messages=$messagesRepo->messagesParUtilisateur($admin);
        foreach($messages[$i]["recu"] as $messageNonLu){
            $messageNonLu->setStatus(true);
            $entityManager->persist($messageNonLu);
            $entityManager->flush($messageNonLu);
        };
        $user=$messages[$i]["recu"][0]->getExpiditeur();

       if($request->isMethod('POST')){
           $message=new Messages();
            $message->setExpiditeur($admin);
            $message->setDestinataire($user);
            $message->setMessage($request->request->get('message'));
            $message->setDate(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush($message);

            $messages=$messagesRepo->messagesParUtilisateur($admin);

            return $this->render('admin/messagerie.html.twig', [
                'controller_name' => 'AdminController',
                'messagesRecu'=>$messages[$i]["recu"],
                'messagesEnvoyer'=>$messages[$i]["envoyer"],
                'messagesParUtilisateur'=>$messages
            ]);

        }   
        return $this->render('admin/messagerie.html.twig', [
            'controller_name' => 'AdminController',
            'messagesRecu'=>$messages[$i]["recu"],
            'messagesEnvoyer'=>$messages[$i]["envoyer"],
            'messagesParUtilisateur'=>$messages
        ]);
    }

    /**
     * @Route("/ensembles", name="ensembles")
     */
    public function ensembles(EnsemblesRepository $ensemblesRepository): Response
    {
        
        
        return $this->render('admin/ensembles.html.twig', [
            'controller_name' => 'AdminController',
            'ensembles'=>$ensemblesRepository->findAll(),
        ]);
    }

     /**
     * @Route("/ensembles/new", name="add_new_ensemble")
     */
    public function addEnsemble(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ensemble = new Ensembles();

        $produit=new Produits();

        $ensemble->addProduit($produit);
        
        $orignalProduits = new ArrayCollection();


        foreach ($ensemble->getProduits() as $produit) {
            $orignalProduits->add($produit);
        }

        $form = $this->createForm(EnsemblesType::class, $ensemble);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // get rid of the ones that the user got rid of in the interface (DOM)
            foreach ($orignalProduits as $product) {
                // check if the exp is in the $user->getExp()
//                dump($user->getExp()->contains($exp));
                if ($ensemble->getProduits()->contains($product) === false) {
                    $entityManager->remove($product);
                }
            }
            $entityManager->persist($ensemble);
            $entityManager->flush();

            return $this->redirectToRoute('edit_ensemble',['id'=> $ensemble->getId()]);

        }
        return $this->render('admin/newEnsemble.html.twig',[
            'formEnsemble' => $form->createView(),
            'button_label'=>'Enregistrer',
            'titre'=>'New Ensemble'
        ]);
    }

    /**
     * @Route("/ensembles/edit/{id}", name="edit_ensemble")
     */
    public function editEnsemble(Request $request,$id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ensemble = $entityManager->getRepository(Ensembles::class)->findOneBy(['id' => $id]);
        
        $orignalProduits = new ArrayCollection();


        foreach ($ensemble->getProduits() as $produit) {
            $orignalProduits->add($produit);
        }

        $form = $this->createForm(EnsemblesType::class, $ensemble);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // get rid of the ones that the user got rid of in the interface (DOM)
            foreach ($orignalProduits as $product) {
                // check if the exp is in the $user->getExp()
//                dump($user->getExp()->contains($exp));
                if ($ensemble->getProduits()->contains($product) === false) {
                    $entityManager->remove($product);
                }
            }
            $entityManager->persist($ensemble);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin');

        }
        return $this->render('admin/newEnsemble.html.twig',[
            'formEnsemble' => $form->createView(),
            'button_label'=>'Enregistrer',
            'titre'=>'Edit Ensemble'
        ]);
    }

    /**
     * @Route("/ensembles/del/{id}", name="delEnsemble")
     */
    public function delEnsemble($id, EnsemblesRepository $ensemblesRepo, EntityManagerInterface $entityManager) 
    {
        $ensemble=$ensemblesRepo->findOneBy(['id'=>$id]);

        $entityManager->remove($ensemble);
        $entityManager->flush($ensemble);

        return $this->render('admin/ensembles.html.twig', [
            'controller_name' => 'AdminController',
            'ensembles'=>$ensemblesRepo->findAll(),
        ]);

    }

    /**
     * @Route("/produits/del/{id}", name="del")
     */
    public function del($id, ProduitsRepository $produitsRepo, EntityManagerInterface $entityManager) 
    {
        $product=$produitsRepo->findOneBy(['id'=>$id]);

        $entityManager->remove($product);
        $entityManager->flush($product);

        return $this->redirectToRoute('admin');

    }

    /**
     * @Route("/Parametrers", name="Parametrers")
     */
    public function Parametrers(): Response
    {
        return $this->render('admin/Parametres.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/Styles/add", name="addStyle")
     */
    public function addStyle( Request $request,EntityManagerInterface $em): Response
    {
        $style=new Styles();
        $form=$this->createForm(StylesType::class,$style);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($style);
            $em->flush($style);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Styles/edit/{id}", name="editStyle")
     */
    public function editStyle(Styles $style, Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(StylesType::class,$style);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($style);
            $em->flush($style);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Styles/del/{id}", name="deleteStyle")
     */
    public function deleteStyle(Styles $style, Request $request,EntityManagerInterface $em, EnsemblesRepository $ensemblesRepository): Response
    {
       
            $em->remove($style);
            $em->flush($style);
            $ensembles=$ensemblesRepository->findAll();
            foreach($ensembles as $ensemble){
                if(!$ensemble->getStyles()[0]){
                    $em->remove($ensemble);
                    $em->flush($ensemble);
                }
            }
        return $this->render('admin/Parametres.html.twig');
    }

    /**
     * @Route("/Saisons/add", name="addSaison")
     */
    public function addSaison( Request $request,EntityManagerInterface $em): Response
    {
        $saison=new Saisons();
        $form=$this->createForm(SaisonsType::class,$saison);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($saison);
            $em->flush($saison);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Saisons/edit/{id}", name="editSaison")
     */
    public function editSaison(Saisons $saison, Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(SaisonsType::class,$saison);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($saison);
            $em->flush($saison);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Saisons/del/{id}", name="deleteSaison")
     */
    public function deleteSaison(Saisons $saison, Request $request,EntityManagerInterface $em, EnsemblesRepository $ensemblesRepository): Response
    {
       
            $em->remove($saison);
            $em->flush($saison);
            $ensembles=$ensemblesRepository->findAll();
            foreach($ensembles as $ensemble){
                if(!$ensemble->getSaisons()[0]){
                    $em->remove($ensemble);
                    $em->flush($ensemble);
                }
            }
        return $this->render('admin/Parametres.html.twig');
    }

    /**
     * @Route("/Types/add", name="addType")
     */
    public function addType( Request $request,EntityManagerInterface $em): Response
    {
        $Type=new Saisons();
        $form=$this->createForm(TypesType::class,$Type);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Type);
            $em->flush($Type);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Types/edit/{id}", name="editType")
     */
    public function editType(Type $Type, Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(TypesType::class,$Type);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Type);
            $em->flush($Type);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Types/del/{id}", name="deleteTypes")
     */
    public function deleteTypes(Type $Type, Request $request,EntityManagerInterface $em, EnsemblesRepository $ensemblesRepository): Response
    {
       
            $em->remove($Type);
            $em->flush($Type);
            $ensembles=$ensemblesRepository->findAll();
            foreach($ensembles as $ensemble){
                if(!$ensemble->getSaisons()[0]){
                    $em->remove($ensemble);
                    $em->flush($ensemble);
                }
            }
        return $this->render('admin/Parametres.html.twig');
    }

    /**
     * @Route("/Tailles/add", name="addTaille")
     */
    public function addTaille( Request $request,EntityManagerInterface $em): Response
    {
        $Taille=new Taille();
        $form=$this->createForm(TaillesType::class,$Taille);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Taille);
            $em->flush($Taille);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Tailles/edit/{id}", name="editTaille")
     */
    public function editTaille(Taille $Taille, Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(TaillesType::class,$Taille);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Taille);
            $em->flush($Taille);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Tailles/del/{id}", name="deleteTaille")
     */
    public function deleteTaille(Taille $Taille, Request $request,EntityManagerInterface $em, EnsemblesRepository $ensemblesRepository): Response
    {
       
            $em->remove($Taille);
            $em->flush($Taille);
            $ensembles=$ensemblesRepository->findAll();
            foreach($ensembles as $ensemble){
                if(!$ensemble->getSaisons()[0]){
                    $em->remove($ensemble);
                    $em->flush($ensemble);
                }
            }
        return $this->render('admin/Parametres.html.twig');
    }

    /**
     * @Route("/Budgets/add", name="addBudget")
     */
    public function addBudgets( Request $request,EntityManagerInterface $em): Response
    {
        $Budget=new Budget();
        $form=$this->createForm(BudgetsType::class,$Budget);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Budget);
            $em->flush($Budget);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Budgets/edit/{id}", name="editBudget")
     */
    public function editBudget(Budget $Budget, Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(BudgetsType::class,$Budget);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Budget);
            $em->flush($Budget);
        }
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/Budgets/del/{id}", name="deleteBudget")
     */
    public function deleteBudget(Budget $Budget, Request $request,EntityManagerInterface $em, EnsemblesRepository $ensemblesRepository): Response
    {
       
            $em->remove($Budget);
            $em->flush($Budget);
            $ensembles=$ensemblesRepository->findAll();
            foreach($ensembles as $ensemble){
                if(!$ensemble->getSaisons()[0]){
                    $em->remove($ensemble);
                    $em->flush($ensemble);
                }
            }
        return $this->render('admin/Parametres.html.twig');
    }

     /**
     * @Route("/Banniere", name="Banniere")
     */
    public function Banniere(Request $request,EntityManagerInterface $em, BanniereRepository $banniereRepo): Response
    {
        $banniere= $banniereRepo->findOneBy(['id'=>1]);
        $form= $this->createForm(BanniereType::class,$banniere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($banniere);
            $em->flush($banniere);
        }
        return $this->render('admin/banniere.html.twig', [
            'banniere'=>$banniere,
            'form' => $form->createView(),
        ]);
    }   
    
    public function nbMessages()
    {
        $messagesRepo=$this->getDoctrine()->getManager()->getRepository(Messages::class);
        $utilisateursRepo=$this->getDoctrine()->getManager()->getRepository(Utilisateurs::class);
        $admin=$utilisateursRepo->findOneBy(['email'=>$this->get('security.token_storage')->getToken()->getUser()->getUsername()]);
        $messages=$messagesRepo->messagesParUtilisateur($admin);
        $i=0;
        foreach($messages as $message){
            foreach($message["recu"] as $msgrecu){
                if(!$msgrecu->getStatus()){
                    $i++;
                }
            }
        }
        return $i;
    }
   
}
