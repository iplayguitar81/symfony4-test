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

        //return the home/index.html twig
        return $this->render('home/index.html.twig', []);
    }


}
