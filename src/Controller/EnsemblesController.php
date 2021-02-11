<?php

namespace App\Controller;

use App\Entity\Ensembles;
use App\Form\EnsemblesType;
use App\Repository\EnsemblesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ensembles")
 */
class EnsemblesController extends AbstractController
{
    /**
     * @Route("/", name="ensembles_index", methods={"GET"})
     */
    public function index(EnsemblesRepository $ensemblesRepository): Response
    {
        return $this->render('ensembles/index.html.twig', [
            'ensembles' => $ensemblesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ensembles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ensemble = new Ensembles();
        $form = $this->createForm(EnsemblesType::class, $ensemble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ensemble);
            $entityManager->flush();

            return $this->redirectToRoute('ensembles_index');
        }

        return $this->render('ensembles/new.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ensembles_show", methods={"GET"})
     */
    public function show(Ensembles $ensemble): Response
    {
        return $this->render('ensembles/show.html.twig', [
            'ensemble' => $ensemble,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ensembles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ensembles $ensemble): Response
    {
        $form = $this->createForm(EnsemblesType::class, $ensemble);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ensembles_index');
        }

        return $this->render('ensembles/edit.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ensembles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ensembles $ensemble): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ensemble->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ensemble);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ensembles_index');
    }
}
