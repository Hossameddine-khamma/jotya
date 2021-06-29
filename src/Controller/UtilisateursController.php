<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\FavorisUtilisateurs;
use App\Entity\Messages;
use App\Entity\Utilisateurs;
use App\Form\FavorisUtilisateursType;
use App\Form\UtilisateursCompteType;
use App\Form\UtilisateursType;
use App\Repository\CommandesRepository;
use App\Repository\MessagesRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UtilisateursRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
* @Route("/utilisateurs")
*/
class UtilisateursController extends AbstractController
{
    

    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(UserInterface $user, CommandesRepository $commandesRepo)
    {
        $commande= $commandesRepo->findOneBy(['utilisateurs'=>$user])->getProduits();
        $prixTotal=0;
        foreach($commande as $ligne){
            if(!$ligne->getPromotion()){
                $prixTotal+=$ligne->getPrix();
            }
            if($ligne->getPromotion()){
                $prixTotal+=$ligne->getPrixPromotion();
            }
        }
    
        \Stripe\Stripe::setApiKey(

            'sk_test_51J6heRK3syLjY4l2NQrdKOCDcZb9DQ5563LRjZydWFVdZ6JWSkaR29bUoqfrlOEcBZ03dnG2c83fJIHiiLWqCnPU00WCxeSHJE'
            );

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Jotya.shop',
                ],
                'unit_amount' => $prixTotal*100,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('succes',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel',[],UrlGeneratorInterface::ABSOLUTE_URL),
          ]);
          return new JsonResponse([ 'id' => $session->id ]);
    }

    /**
     * @Route("/succes", name="succes")
     */
    public function succes()
    {
        return $this->render('utilisateurs/succes.html.twig');
    }

    /**
     * @Route("/cancel", name="cancel")
     */
    public function cancel()
    {
        return $this->render('utilisateurs/cancel.html.twig');
    }

    /**
     * @Route("/compte", name="compte")
     */
    public function compte(Request $request, Security $security,EntityManagerInterface $em,CommandesRepository $commandesRepo,UtilisateursRepository $utilisateursRepo)
    {
        $user=$security->getUser();
        $utilisateur= $utilisateursRepo->findOneBy(['email'=> ($user->getUsername())]);
        if(!$utilisateur->getFavorisUtilisateurs()){
            $favorisUtilisateurs= new FavorisUtilisateurs();
        }
        if($utilisateur->getFavorisUtilisateurs()){
            $favorisUtilisateurs= $utilisateur->getFavorisUtilisateurs();
        }
        
        


        $formCompte=$this->createForm(UtilisateursCompteType::class,$user);
        $formTaille=$this->createForm(FavorisUtilisateursType::class,$favorisUtilisateurs);

        $formTaille->handleRequest($request);

        if($formTaille->isSubmitted() && $formTaille->isValid()){
            
            $utilisateur->setFavorisUtilisateurs($favorisUtilisateurs);
            $em->persist($utilisateur);
            $em->flush();
        }

        $commande= $utilisateursRepo->findOneBy(['email'=> ($user->getUsername())])->getFavorisProduits();
        
        return $this->render('utilisateurs/compte.html.twig',[
            'utilisateursCompteForm'=> $formCompte->createView(),
            'favorisUtilisateursForm'=> $formTaille->createView(),
            'commande'=>$commande,
        ]);
    }

     /**
     * @Route("/favoris/{id}-{type}-{returnId}", name="favoris")
     */
    public function favoris($type,$returnId, $id ,EntityManagerInterface $em, UserInterface $user, ProduitsRepository $produitsRepo,UtilisateursRepository $utilisateursRepo)
    {

        $produit=$produitsRepo->findOneBy(['id' => $id]);

        $utilisateur=$utilisateursRepo->findOneBy(['email'=> ($user->getUsername())]);

        $utilisateur->addFavorisProduit($produit);

        $em->persist($utilisateur);

        $em->flush();

        if($type == 'P'){
            return $this->redirectToRoute('productDiscription',['id'=>$returnId]);
        }elseif($type == 'E'){
            return $this->redirectToRoute('styleDetails',['id'=>$returnId]);
        }elseif($type=='PN'){
            return $this->redirectToRoute('panier');
        }
        elseif($type=='PNF'){
            $utilisateur->getCommandes()[0]->removeProduit($produit);
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('panier');
        }

    }

     /**
     * @Route("/favoris/supprimer/{id}}", name="favorisRemove")
     */
    public function favorisRemove($id,EntityManagerInterface $em, UserInterface $user, ProduitsRepository $produitsRepo,UtilisateursRepository $utilisateursRepo)
    {
        $utilisateur=$utilisateursRepo->findOneBy(['email'=> ($user->getUsername())]);
        $produit=$produitsRepo->findOneBy(['id' => $id]);

        $utilisateur->removeFavorisProduit($produit);

        $em->persist($utilisateur);
        $em->flush();

        return $this->redirectToRoute('compte');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request,Security $security, MessagesRepository $messagesRepo, UtilisateursRepository $utilisateursRepo)
    {
        $user=$security->getUser();
        $message=new Messages();
        

        $messagesEnvoyer= $messagesRepo->findMessagesEnvoyer($user);
        $messagesRecu= $messagesRepo->findMessagesRecu($user);

        if($request->isMethod('POST')){
            $message->setExpiditeur($user);
            $utilisateurs=$utilisateursRepo->findAll();
            foreach($utilisateurs as $admin){
                $roles=$admin->getRoles();
                if(in_array("ROLE_ADMIN",$roles)){
                $message->setDestinataire($admin);
                }
            }
            $message->setMessage($request->request->get('message'));
            $message->setDate(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush($message);

            $messagesEnvoyer= $messagesRepo->findMessagesEnvoyer($user);
            $messagesRecu= $messagesRepo->findMessagesRecu($user);

            return $this->render('utilisateurs/contact.html.twig',[
                'messagesEnvoyer' => $messagesEnvoyer,
                'messagesRecu' => $messagesRecu
            ]);

        }
        
        return $this->render('utilisateurs/contact.html.twig',[
            'messagesEnvoyer' => $messagesEnvoyer,
            'messagesRecu' => $messagesRecu
        ]);
    }

    

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(UserInterface $user, CommandesRepository $commandesRepo)
    {
        $commande= $commandesRepo->findOneBy(['utilisateurs'=>$user])->getProduits();
        $prixTotal=0;
        foreach($commande as $ligne){
            if(!$ligne->getPromotion()){
                $prixTotal+=$ligne->getPrix();
            }
            if($ligne->getPromotion()){
                $prixTotal+=$ligne->getPrixPromotion();
            }
        }

        return $this->render('utilisateurs/panier.html.twig',[
            'commande'=>$commande,
            'prixTotal'=>$prixTotal
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="ajouterPanier")
     */
    public function AjouterPanier($id ,ProduitsRepository $produitsRepo, CommandesRepository $commandesRepo,UserInterface $user, EntityManagerInterface $em)
    {
        if($commandesRepo->findOneBy(['utilisateurs'=>$user]) == null){
            $commande=new Commandes();
            $commande->setUtilisateurs($user);
        }
        if($commandesRepo->findOneBy(['utilisateurs'=>$user]) != null){
            $commande=$commandesRepo->findOneBy(['utilisateurs'=>$user]);
        }
        
        $commande->addProduit($produitsRepo->findOneBy(['id'=> $id]));

        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/Supprimer/{id}", name="SupprimerPanier")
     */
    public function SupprimerPanier($id ,ProduitsRepository $produitsRepo, CommandesRepository $commandesRepo,UserInterface $user, EntityManagerInterface $em)
    {
       
        $commande=$commandesRepo->findOneBy(['utilisateurs'=>$user]);
     
        $commande->removeProduit($produitsRepo->findOneBy(['id'=> $id]));

        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('panier');
    }

}
