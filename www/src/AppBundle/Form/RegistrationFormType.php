<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 030 30.10.17
 * Time: 13:46
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');

    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }


}
