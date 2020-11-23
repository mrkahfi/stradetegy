<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Jariff\AdminBundle\Form\Subscriber\LeadActivitySubscriber;

class LeadActivityType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateScheduled', 'date', array(
                    'required' => false,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'attr'     => array(
                        'class' => 'datetime',
                        // 'data-format' => 'yyyy-MM-dd hh:mm:ss',
                        'data-format' => 'yyyy-MM-dd',
                )))
            ->add('description', 'textarea', array(
                    'required'      => true,
                ))
            ->add('event', 'hidden', array())
            ->add('priority', 'choice', array(
                    'required'    => false,
                    'empty_value' => '---',
                    'choices'     => array(
                        'High'   => 'High',
                        'Medium' => 'Medium',
                        'Low'    => 'Low',
                )))
            ->add('result', 'entity',
                array(
                    'property' => 'name',
                    'required'      => false,
                    'class'     => 'JariffAdminBundle:LeadActivityResult',
                    'empty_value' => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('status', 'choice', array(
                    'required'    => true,
                    'empty_value' => '---',
                    'choices'     => array(
                        'Scheduled' => 'Scheduled',
                        'Complete' => 'Complete',
                )))
            ->add('type', 'entity',
                array(
                    'property' => 'name',
                    'required'      => false,
                    'class'     => 'JariffAdminBundle:LeadActivityType',
                    'empty_value' => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e'); // ->where('c.public = true')
                    },
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 75%;',
                )))
            ->add('assigned', 'entity',
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
            ->addEventSubscriber(new LeadActivitySubscriber());
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\AdminBundle\Entity\LeadActivity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leadactivity';
    }
}
