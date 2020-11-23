<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LeadContactType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('callTime', 'entity', array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:CallTime',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 80%;',
                )))
            ->add('callTimeOther', 'text', array(
                    'required'      => false,
                    ))
            ->add('ccType', 'jariff_cc', array(
                    'required'      => false,
                    'label' => 'Credit card type',
                    ))
            ->add('country', 'jariff_country', array(
                    'required'          => false,
                    'preferred_choices' => array(
                        "United States"  => "United States",
                    ),
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('dontCall', 'checkbox', array(
                    'required'      => false,
                ))
            ->add('dontEmail', 'checkbox', array(
                    'required'      => false,
                ))
            ->add('decisionMaker', 'checkbox', array(
                    'required'      => false,
                ))
            ->add('email', 'text', array(
                    'required'      => false,
                ))
            ->add('expired', 'date', array(
                    'required'      => false,
                ))
            ->add('facebook', 'text', array(
                    'required'      => false,
                ))
            ->add('firstName', 'text', array(
                    'required'      => false,
                ))
            ->add('ip', 'text', array(
                    'required'      => false,
                ))
            ->add('jobTitle', 'text', array(
                    'required'      => false,
                ))
            ->add('language', 'text', array(
                    'required'      => false,
                ))
            ->add('lastName', 'text', array(
                    'required'      => false,
                ))
            ->add('linkedin', 'text', array(
                    'required'      => false,
                ))
            ->add('number', 'text', array(
                    'label'         => 'CC Number',
                    'required'      => false,
                ))
            ->add('password', 'text', array(
                    'required'      => false,
                ))
            ->add('paypal', 'text', array(
                    'required'      => false,
                ))
            ->add('payment', 'jariff_payment', array(
                    'required'      => false,
                ))
            ->add('phone', 'text', array(
                    'required'      => false,
                ))
            ->add('salutation', 'jariff_salutation', array(
                    'required'      => false,
                ))
            ->add('secureCode', 'text', array(
                    'required'      => false,
                ))
            ->add('skype', 'text', array(
                    'required'      => false,
                ))
            ->add('street', 'text', array(
                    'required'      => false,
                ))
            ->add('city', 'text', array(
                    'required'      => false,
                ))
            ->add('twitter', 'text', array(
                    'required'      => false,
                ))
            ->add('website', 'text', array(
                    'required'      => false,
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\AdminBundle\Entity\LeadContact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leadcontact';
    }
}
