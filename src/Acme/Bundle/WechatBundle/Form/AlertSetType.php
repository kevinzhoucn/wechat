<?php

namespace Acme\Bundle\WechatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertSetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('channel1', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
            ->add('channel2', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
            ->add('channel3', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
            ->add('wechat', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
            ->add('sms', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
            ->add('voice', 'checkbox', array('attr' => array('class' => 'weui_switch'), 'label' => false, 'required' => false))
        ;
    }    
}
