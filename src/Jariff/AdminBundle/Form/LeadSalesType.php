<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LeadSalesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('primary', 'checkbox', array(
                    'required'  => false,
                ))
            ->add('sales', 'entity',
                array(
                    'property' => 'name',
                    'required'  => true,
                    'class'     => 'JariffAdminBundle:Admin',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            // ->where('c.public = true')
                            ;
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                    )
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\AdminBundle\Entity\LeadSales'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leadsales';
    }
}
