<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Jariff\AdminBundle\Form\LeadContactType;
use Jariff\AdminBundle\Form\LeadDataInterestType;
use Jariff\AdminBundle\Form\LeadSalesType;

class LeadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business', 'text', array(
                    'label'    => 'Business name',
                    'required' => true,
                ))
            ->add('businessType', 'entity',
                array(
                    'property' => 'name',
                    'required'  => false,
                    'class'     => 'JariffAdminBundle:BusinessType',
                    'empty_value' => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('businessTypeOther', 'text', array(
                    'required'      => false,
                ))
            ->add('competitor', 'entity',
                array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:Competitor',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('competitorOther', 'text', array(
                    'required'      => false,
                    ))
            ->add( 'contact', 'collection', 
                array(
                    'type'         => new LeadContactType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'prototype'    => true,
                    'by_reference' => false,
                    'label'        => ' ',
                ))
            ->add('campaign', 'text', array(
                    'required'      => false,
                    ))
            ->add('competitorStatus', 'choice', array(
                    'required'    => false,
                    'empty_value' => '---',
                    'choices'     => array(
                        'Comparison Shopping' => 'Comparison Shopping',
                        'Active Subscriber'   => 'Active Subscriber',
                        'Past Subscriber'     => 'Past Subscriber',
                )))
            ->add('competitorDateEnd', 'date', array(
                    'required' => false,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'attr'     => array(
                        'class' => 'datetime',
                        // 'data-format' => 'yyyy-MM-dd hh:mm:ss',
                        'data-format' => 'yyyy-MM-dd',
                )))
            ->add( 'dataInterest', 'collection', 
                array(
                    'type'         => new LeadDataInterestType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'prototype'    => true,
                    'by_reference' => false,
                    'label'        => ' '
                ))
            ->add('description', 'textarea', array(
                    'required'      => false,
                    ))
            ->add('dateReady', 'date', array(
                    'required' => false,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'attr'     => array(
                        'class' => 'datetime',
                        // 'data-format' => 'yyyy-MM-dd hh:mm:ss',
                        'data-format' => 'yyyy-MM-dd',
                )))
            ->add('flag', 'entity',
                array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:Flag',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('notes', 'textarea', array(
                    'required'      => false,
                    ))
            ->add('owner', 'entity',
                array(
                    'property'      => 'name',
                    'required'      => false,
                    'empty_value'   => '---',
                    'class'         => 'JariffAdminBundle:Admin',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('product', 'textarea', array(
                    'required'      => false,
                    ))
            ->add('sales', 'collection', array(
                    'type'         => new LeadSalesType(),
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'prototype'    => true,
                    'by_reference' => false,
                    'label'        => ' '
                ))
            ->add('source', 'entity', array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:LeadSource',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('sourceOther', 'text', array(
                    'required'      => false,
                ))
            ->add('stage', 'entity',
                array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:LeadStage',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                    )
                ))
            ->add('stageReason', 'text', array(
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
            'data_class' => 'Jariff\AdminBundle\Entity\Lead'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lead';
    }
}
