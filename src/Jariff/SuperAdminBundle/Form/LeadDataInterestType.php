<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LeadDataInterestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dataInterest', 'entity',
                array(
                    'property'      => 'name',
                    'required'      => false,
                    'class'         => 'JariffAdminBundle:DataInterest',
                    'label'         => ' ',
                    'empty_value'   => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                    )
                ))
            ->add('dataInterestOther', 'text', array(
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
            'data_class' => 'Jariff\AdminBundle\Entity\LeadDataInterest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leaddatainterest';
    }
}
