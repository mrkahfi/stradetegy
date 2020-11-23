<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class InvoiceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billToName')
            ->add('billToAdress')
            ->add('billToEmail')
            ->add('billToPhone')
            ->add('date', 'date', array(
                    'required' => false,
                    'widget'   => 'single_text',
                    'format'   => 'yyyy-MM-dd',
                    'attr'     => array(
                        'class' => 'datetime',
                        // 'data-format' => 'yyyy-MM-dd hh:mm:ss',
                        'data-format' => 'yyyy-MM-dd',
                )))
            // ->add('status', 'choice', array(
            //         'required'    => true,
            //         'empty_value' => '---',
            //         'choices'     => array(
            //             'paid'   => 'Paid',
            //             'outstanding' => 'Outstanding',
            //     )))
            // ->add('amount')
            ->add('description', 'textarea', array(
                    'attr' => array(
                        'rows' => '5',
                        'style' => 'width: 75%;',
                )))
            ->add('subscription', 'entity', array(
                    'required'  => true,
                    'class'     => 'JariffMemberBundle:MemberSubscription',
                    'empty_value' => '---',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'desc');
                    },
                    'attr' => array(
                        'style' => 'width: 75%;',
                )))
            ->add('sales')
            ->add('member', 'hidden_member')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\Invoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jariff_memberbundle_invoice';
    }
}
