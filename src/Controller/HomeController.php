<?php

namespace App\Controller;

use App\Entity\Hospital;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#use App\Entity\Hospital;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        //query the hospital table in the database to gather all hospital records...
        $hospitals = $this->getDoctrine()
            ->getRepository(Hospital::class)
            ->findAll();

        //return the home/index.html twig with the hospitals object...
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'hospitals' => $hospitals
        ]);
    }
}
