<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/plans", name="bonPlans")
     */
    public function plans()
    {
        return $this->render('home/plans.html.twig', [
            'controller_name' => 'Bon plans',
        ]);
    }
    /**
     * @Route("/nouveau", name="nouveau")
     */
    public function nouveau()
    {
        return $this->render('home/nouveau.html.twig', [
            'controller_name' => 'Nouveau',
        ]);
    }
    /**
     * @Route("/homme", name="homme")
     */
    public function indexHomme()
    {
        return $this->render('homme/index.html.twig', [
            'controller_name' => 'Homme',
        ]);
    }
     /**
     * @Route("/homme/styles", name="hommeStyles")
     */
    public function hommeStyles()
    {
        return $this->render('homme/styles.html.twig', [
            'controller_name' => 'Homme',
        ]);
    }
    /**
     * @Route("/homme/styles/products", name="productsdiscription")
     */
    public function hommeproducts()
    {
        return $this->render('homme/productsdiscription.html.twig', [
            'controller_name' => 'Homme',
        ]);
    }
     /**
     * @Route("/femme", name="femme")
     */
    public function indexFemme()
    {
        return $this->render('femme/index.html.twig', [
            'controller_name' => 'Femme',
        ]);
    }
    /**
     * @Route("/enfants", name="enfants")
     */
    public function indexEnfants()
    {
        return $this->render('enfants/index.html.twig', [
            'controller_name' => 'Enfants',
        ]);
    }
   

}
