<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Form\HospitalFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HospitalController extends AbstractController
{

    /**
     * @Route("/hospitals", name="app_hospitals")
     */
    public function index()
    {
        //publicly accessible to end users to see all hospital listings...

        //query the hospital table in the database to collect all hospital records...
        $hospitals = $this->getDoctrine()
            ->getRepository(Hospital::class)
            ->findAll();

        //return & render the hospital/index.html twig template with the hospital objects
        return $this->render('hospital/index.html.twig', ['hospitals' => $hospitals ]);
    }


    /**
     * @Route("/admin/hospitals/create", name="app_hospitals_create")
     */
    public function create(EntityManagerInterface $em, Request $request)
    {

        //deny access unless the ROLE_ADMIN is assigned to a logged in user...
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //create form using the HospitalFormType class as the blueprint...
        $form = $this->createForm(HospitalFormType::class);

        // handleRequest only processes the request...
        // when it is a POST request not a GET request
        $form->handleRequest($request);

        //if form is submitted and is valid process form input and create new hospital record in the db
        if($form->isSubmitted() && $form->isValid()) {


            //get the data from the form request
            $data= $form->getData();

            // create new hospital record in the database with the fields from form request...
            $hospital = new Hospital();
            $hospital->setName($data['name']);
            $hospital->setAddress($data['address']);
            $hospital->setPhoneNumber($data['phoneNumber']);
            // not used for this project but handy way to store user who created record...
            //$hospital->setScribe($this->getUser());

            //use the entity manager to persist and flush, saving the new record in the db...
            $em->persist($hospital);
            $em->flush();

            return $this->redirectToRoute('home');


        }

        return $this->render('hospital/create.html.twig', [
            'hospitalForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/hospitals", name="app_hospitals_admin")
     */
    public function adminList(){

        //deny access unless the ROLE_ADMIN is assigned to a logged in user...
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //query the hospital table in the database to collect all hospital records...
        $hospitals = $this->getDoctrine()
            ->getRepository(Hospital::class)
            ->findAll();

        return $this->render('hospital/admin-list.html.twig', [
            'hospitals' => $hospitals
        ]);

    }



}
