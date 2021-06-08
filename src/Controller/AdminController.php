<?php

namespace App\Controller;

use App\Entity\Banniere;
use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Form\BanniereType;
use App\Form\EnsemblesType;
use App\Form\ProduitsType;
use App\Form\StylesType;
use App\Repository\BanniereRepository;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/ensembles", name="ensembles")
     */
    public function ensembles(): Response
    {
        return $this->render('admin/ensembles.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/messagerie", name="messagerie")
     */
    public function messagerie(): Response
    {
        return $this->render('admin/messagerie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
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
     * @Route("/Styles/edit/{id}", name="editStyle")
     */
    public function editStyle($id): Response
    {
        $form=$this->createForm(StylesType::class);
        return $this->render('admin/parametersForms.html.twig', [
            'form'=>$form->createView()
        ]);
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
            'button_label'=>'Enregistrer'
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
            'button_label'=>'Enregistrer'
        ]);
    }
    
   
}
