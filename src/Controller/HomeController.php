<?php

namespace App\Controller;

use App\Entity\Hospital;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#use App\Entity\Hospital;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {

        $hospitals = $this->getDoctrine()
            ->getRepository(Hospital::class)
            ->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ],['hospitals' => $hospitals]);
    }
}
