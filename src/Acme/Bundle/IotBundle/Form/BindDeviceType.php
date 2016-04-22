<?php

namespace Acme\Bundle\IotBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BindDeviceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone')
            ->add('sn')
            ->add('phone1', null, array('required' => false))
            ->add('phone2', null, array('required' => false))
        ;
    }    
}
