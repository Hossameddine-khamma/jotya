<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
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
        $Manager = $this->getDoctrine()->getManager();
        $produit = new Produits();
        $form= $this->createForm(ProduitsType::class,$produit);

        return $this->render('admin/newProduct.html.twig',[
            'formProduits' => $form->createView()
        ]);
    }
}
