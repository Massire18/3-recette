<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    /**
     * @Route("/types", name="types")
     */
    public function index(TypeRepository $repo)
    {
        $types = $repo->findAll();
        return $this->render('type/types.html.twig',[
            "lesTypes" => $types
        ]);
    }

    /**
     * @Route("/admin/type/create", name="ajoutType")
     * @Route("/admin/type/{id}", name="modifType",methods="GET|POST")
     */
    public function ajoutEtModif(Type $type = null, Request $request, EntityManagerInterface $om)
    {  
        if(!$type){
            $type= new Type();
        }
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             $om->persist($type);
             $om->flush();
             $this->addFlash('success', "L'action a été réalisée");
            return $this->redirectToRoute("admin_type");
         }
        return $this->render('admin_type/ajoutEtModif.html.twig',[
            "type" => $type,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/type/{id}", name="supType",methods="delete")
     */
    public function suppression(Type $type, Request $request, EntityManagerInterface $om)
    {
        if($this->isCsrfTokenValid("SUP". $type->getId(),$request->get('_token')))
        {
            $om->remove($type);
            $om->flush();
            $this->addFlash("success","La suppression a été effectuée");

            return $this->redirectToRoute("admin_type");

        }
    }
}
