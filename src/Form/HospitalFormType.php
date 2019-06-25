<?php
/**
 * Created by PhpStorm.
 * User: iplayguitar81
 * Date: 6/24/19
 * Time: 7:49 PM
 */

namespace App\Form;


use App\Entity\Hospital;

//can be called after running composer require form...
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HospitalFormType extends AbstractType
{
    //build form and add properties to collect...
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, ['help' => 'Enter Hospital Name'])
            ->add('phoneNumber', TextType::class, ['help' => 'Enter Hospital Phone Number'])
            ->add('address', TextareaType::class, ['help' => 'Enter Hospital Address']);
    }


    //configure the options to bind form to the Hospital class...
    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults([

            'data_class' => Hospital::class
        ]);

    }


}