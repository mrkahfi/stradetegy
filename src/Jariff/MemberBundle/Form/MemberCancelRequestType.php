<?php

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberCancelRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reason', 'choice', array(
                'choices' => array(
                    'No Longer Needed' => 'No Longer Needed',
                    'Trouble with search application' => 'Trouble with search application',
                    'Too Expensive' => 'Too Expensive',
                    'other' => 'Other',
                ),
                'label' => 'Reason for cancelling',
                'multiple'     => true,
                'expanded' => true,
                'attr' => array('class' => 'j-choice-reason', 'col_md' => 'col-md-12'),
                'label_attr' => array('class' => 'col-md-12')
            ))
            ->add('other', 'text', array(
                'label' => '',
                'attr' => array('class' => 'form-control j-reason-other', 'col_md' => 'col-md-12','style' => 'display:none','placeholder' => 'Please insert your other reason'),
                'label_attr' => array('class' => 'col-md-12'),
                'mapped' => false,
                'required' => false
            ))
            ->add('experience', null, array(

                'label' => 'What could we have done to make your experience better?',
                'attr' => array('class' => 'form-control', 'col_md' => 'col-md-12'),
                'label_attr' => array('class' => 'col-md-12')
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberCancelRequest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jariff_memberbundle_membercancelrequest';
    }
}
