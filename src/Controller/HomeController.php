<?php

namespace App\Controller;

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

        $repository = $this->getDoctrine()->getRepository(Hospital::class);
        $hospitals = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ],['hospitals' => $hospitals]);
    }
}
