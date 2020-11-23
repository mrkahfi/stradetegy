<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('type', 'choice', array(
                'label' => 'Payment type',
                'choices'   => array(
                    'cc'       => 'Credit Card',  
                    'paypal'   => 'PayPal',  
                    'check'    => 'Check',  
                    'bankwire' => 'Bankwire',
                ),
                'attr' => array(
                )))
            ->add('date', 'date', array(
                    'required' => false,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'attr'     => array(
                        'class' => 'datetime',
                        // 'data-format' => 'yyyy-MM-dd hh:mm:ss',
                        'data-format' => 'yyyy-MM-dd',
                )))
            ->add('note', 'textarea', array(
                    'attr' => array(
                        'rows' => '5',
                        'style' => 'width: 75%;',
                )))
            // ->add('admin')
            ->add('member', 'hidden_member')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\Payment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payment';
    }
}
