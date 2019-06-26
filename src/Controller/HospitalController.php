<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Hospital;
use App\Form\ContactFormType;
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


            //get the data from the form request and use bound data to create Hospital db record...
            $hospital= $form->getData();



            // create new hospital record manually in the database with the fields from form request...
            // $hospital = new Hospital();
            // $hospital->setName($data['name']);
            // $hospital->setAddress($data['address']);
            // $hospital->setPhoneNumber($data['phoneNumber']);
            // not used for this project but handy way to store user who created record...
            //$hospital->setScribe($this->getUser());

            //use the entity manager to persist and flush, saving the new record in the db...
            $em->persist($hospital);
            $em->flush();

            //create a flash message to let admin user know Hospital was created successfully!
            $this->addFlash('success', 'Hospital Created!  Nice work! :)');

            //redirect to the admin listing page
            return $this->redirectToRoute('app_hospitals_admin');


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


    /**
     * @Route("admin/hospitals/{id}/edit", name="app_hospital_edit")
     */
    public function edit(Hospital $hospital, Request $request, EntityManagerInterface $em) {

        //deny access unless the ROLE_ADMIN is assigned to a logged in user...
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //create form using the HospitalFormType class as the blueprint, and passing hospital object...
        $form = $this->createForm(HospitalFormType::class, $hospital);

        // handleRequest only processes the request...
        // when it is a POST request not a GET request
        $form->handleRequest($request);

        //if form is submitted and is valid process form input and update the hospital record in the db
        if($form->isSubmitted() && $form->isValid()) {

            //use the entity manager to persist and flush, updating and saving the passed record in the db...
            $em->persist($hospital);
            $em->flush();

            //create a flash message to let admin user know Hospital was created successfully!
            $this->addFlash('success', 'Hospital Updated!');

            //redirect back to this edited hospital's edit form...
            return $this->redirectToRoute('app_hospital_edit', ['id' => $hospital->getId()]);


        }

        //return rendered edit page with form and hospital data...
        return $this->render('hospital/edit.html.twig', [
            'hospitalForm' => $form->createView(),
            'hospital' => $hospital
        ]);

    }

    /**
     * @Route("admin/hospitals/{id}", name="app_hospital_show")
     */
    public function show(Hospital $hospital, Request $request, EntityManagerInterface $em) {


        $contact = new Contact();

        //create form using the HospitalFormType class as the blueprint...
        $form = $this->createForm(ContactFormType::class, $contact);

        // handleRequest only processes the request...
        // when it is a POST request not a GET request
        $form->handleRequest($request);

        //if form is submitted and is valid process form input and create new hospital record in the db
        if($form->isSubmitted() && $form->isValid()) {

            //get the data from the form request and use bound data to create Hospital db record...
            $contact= $form->getData();

            //set the hospital_id to the appropriate hospital object a user is leaving a contact message for...
            $contact->setHospital($hospital);

            //use the entity manager to persist and flush, saving the new record in the db...
            $em->persist($contact);
            $em->flush();

            //create a flash message to let end user know Contact message was created successfully!
            $this->addFlash('success', 'Contact Message Created!  We will review what you sent to us ASAP and get back to you ASAP!');

            //redirect to the admin listing page
            return $this->redirectToRoute('home');

        }


        //return rendered show page with queried hospital object
        return $this->render('hospital/show.html.twig', [
            'contactForm' => $form->createView(),
            'hospital' => $hospital,

        ]);
    }


    /**
     * @Route ("/hospitals/delete/{id}", name="app_hospital_delete")
     */
    public function deleteHospital($id, EntityManagerInterface $em){

        //find hospital by passed ID
        $hospital = $this->getDoctrine()
            ->getRepository(Hospital::class)
            ->find($id);

        //use entity manager interface to remove hospital and flush the db...
        $em->remove($hospital);
        $em->flush();

        //create a flash message to let end user know Hospital and associated child Contact messages...
        // were deleted successfully...
        $this->addFlash('success', 'Hospital Deleted Along with Any Contact Messages Associated to a Hospital!');

        //redirect to the admin listing page
        return $this->redirectToRoute('app_hospitals_admin');
    }


}
