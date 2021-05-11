<?php

namespace App\Controller;

use App\Entity\FavorisUtilisateurs;
use App\Entity\Messages;
use App\Entity\Utilisateurs;
use App\Form\FavorisUtilisateursType;
use App\Form\UtilisateursCompteType;
use App\Form\UtilisateursType;
use App\Repository\MessagesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
* @Route("/utilisateurs")
*/
class UtilisateursController extends AbstractController
{
    /**
     * @Route("/nouveau", name="nouveau")
     */
    public function nouveau(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
     * @Route("/compte", name="compte")
     */
    public function compte(Request $request, Security $security)
    {
        $user=$security->getUser();
        $favorisUtilisateurs=new FavorisUtilisateurs();
        $favorisUtilisateurs->setUtilisateurs($user);
        $formCompte=$this->createForm(UtilisateursCompteType::class,$user);
        $formTaille=$this->createForm(FavorisUtilisateursType::class,$favorisUtilisateurs);
        
        return $this->render('utilisateurs/compte.html.twig',[
            'utilisateursCompteForm'=> $formCompte->createView(),
            'favorisUtilisateursForm'=> $formTaille->createView()
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request,Security $security, MessagesRepository $messagesRepo)
    {
        $user=$security->getUser();
        $message=new Messages();
        

        $messagesEnvoyer= $messagesRepo->findMessagesEnvoyer($user);
        $messagesRecu= $messagesRepo->findMessagesRecu($user);

        if($request->isMethod('POST')){
            dump('test');
            $message->setExpiditeur($user);
            $message->setMessage($request->request->get('message'));
            $message->setDate(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush($message);

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
     * @Route("/panier/", name="panier")
     */
    public function panier(Request $request,Security $security, MessagesRepository $messagesRepo)
    {

        return $this->render('utilisateurs/panier.html.twig',[
            
        ]);
    }

}
