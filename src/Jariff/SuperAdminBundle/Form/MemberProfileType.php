<?php

namespace Jariff\AdminBundle\Form;

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
            ->add('country', 'jariff_country', array(
                'label' => 'What country do you live in?',
                'preferred_choices' => array(
                    "United States"  => "United States",
                ),
                'attr' => array(
                    'class' => 'select2',
                    'style' => 'width: 90%;',
                )))
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('salutation', 'jariff_salutation', array())
            ->add('state')
            ->add('member', 'hidden_member')
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
        return 'memberprofile';
    }
}
