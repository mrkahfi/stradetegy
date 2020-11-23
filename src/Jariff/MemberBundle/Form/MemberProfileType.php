<?php

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberProfileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salutation','choice', array(
                'choices' => array(
                    'Mr.' => 'Mr.',
                    'Ms.' => 'Ms.',
                    'Mrs.' => 'Mrs.',
                    'Miss' => 'Miss',
                    'Dr.' => 'Dr.',
                    'Prof.' => 'Prof.',
                ),
                'label' => 'Title'
            ))

            // ->add('dateExpired')
//            ->add('dateRegistration')
//            ->add('email')
            ->add('firstName',null,array(

                'label' => 'First Name'
            ))
            ->add('lastName',null,array(

                'label' => 'Last Name'
            ))
            ->add('phone',null,array(

                'label' => 'Phone'
            ))
            ->add('country',null,array(

                'label' => 'Country'
            ))
            ->add('state',null,array(

                'label' => 'State'
            ))
//            ->add('status')
//            ->add('manager')
//            ->add('member')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberProfile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jariff_memberbundle_memberprofile';
    }
}
