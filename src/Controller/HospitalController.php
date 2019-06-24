<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HospitalController extends AbstractController
{
    /**
     * @Route("/admin/hospitals/create", name="hospital")
     */
    public function create()
    {

        //deny access unless the ROLE_ADMIN is assigned to a logged in user...
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('hospital/create.html.twig', [
            'controller_name' => 'HospitalController',
        ]);
    }
}
