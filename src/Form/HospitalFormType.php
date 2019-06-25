<?php
/**
 * Created by PhpStorm.
 * User: iplayguitar81
 * Date: 6/24/19
 * Time: 7:49 PM
 */

namespace App\Form;

//can be called after running composer require form...
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HospitalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('phoneNumber')
            ->add('address');

    }


}