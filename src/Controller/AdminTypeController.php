<?php

namespace App\Controller;

use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminTypeController extends AbstractController
{
    /**
     * @Route("/admin/type", name="admin_type")
     */
    public function index(TypeRepository $repo)
    {
        $types = $repo->findAll();
        return $this->render('admin_type/adminType.html.twig', [
            "types" => $types
        ]);
    }
}
