<?php
/**
 * Created by PhpStorm.
 * User: iplayguitar81
 * Date: 6/25/19
 * Time: 6:08 PM
 */

namespace App\Form;


use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{

    //build form and add properties to collect...
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('subject', TextType::class, ['help' => 'Enter Subject'])
            ->add('email', TextType::class, ['help' => 'Enter Your Email'])
            ->add('message', TextareaType::class, ['help' => 'Enter Your Message']);

    }


    //configure the options to bind form to the Contact class...
    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults([

            'data_class' => Contact::class
        ]);

    }

}