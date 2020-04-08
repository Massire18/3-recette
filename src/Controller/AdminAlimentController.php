<?php

namespace App\Controller;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAlimentController extends AbstractController
{
    /**
     * @Route("/aliment", name="admin_aliment")
     */
    public function index(AlimentRepository $repository)
    {
        $aliments = $repository->findAll();
        return $this->render('admin_aliment/adminAliment.html.twig', [
            'aliments' => $aliments
        ]);
    }
    /**
     * @Route("/aliment/creation", name="admin_aliment_creation")
     * @Route("/aliment/{id}", name="admin_aliment_modification",methods="GET|POST")
     */
    public function ajoutEtModif(Aliment $aliment = null, Request $request, EntityManagerInterface $entityManager )
    {
        
            if(!$aliment)
            {
                $aliment = new Aliment();
            }

        $form = $this->createForm(AlimentType::class,$aliment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $aliment->getId() !== null;
           $entityManager->persist($aliment);
           $entityManager->flush();
           $this->addFlash("success", ($modif) ? "La modification a été effectuée" :"L'ajout a été effectuée" );
           return $this->redirectToRoute("admin_aliment");
        }

        return $this->render('admin_aliment/modifEtAjout.html.twig', [
            "aliment" => $aliment,
            "form" => $form->createView(),
            "isModification" => $aliment->getId() !== null
        ]);
    }

    /**
     * @Route("/aliment/{id}", name="admin_aliment_suppression", methods="delete")
     */
    public function suppression(Aliment $aliment, Request $request, EntityManagerInterface $entityManager)
    {
        if($this->isCsrfTokenValid("SUP". $aliment->getId(),$request->get('_token'))){
            $entityManager->remove($aliment);
            $entityManager->flush();
            $this->addFlash("success","La suppression a été effectuée");
            return $this->redirectToRoute("admin_aliment");
        }
        
    }
}
