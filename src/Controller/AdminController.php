<?php

namespace App\Controller;

use App\Entity\Ensembles;
use App\Entity\Produits;
use App\Form\EnsemblesType;
use App\Form\ProduitsType;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/produits/new", name="add_new_product")
     */
    public function addProduct(Request $request): Response
    {
        
        $produit = new Produits();
        $form= $this->createForm(ProduitsType::class,$produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/newProduct.html.twig',[
            'produit'=>$produit,
            'formProduits' => $form->createView(),
            'button_label'=>'Ajouter'
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
