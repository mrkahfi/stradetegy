<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberEmailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('altbody', 'textarea', array(
                    'attr' => array(
                        'rows' => '10',
                        'style' => 'width: 75%;',
                )))
            ->add('body', 'textarea', array(
                    'attr' => array(
                        'class' => 'tinymce',
                        'rows' => '10',
                        'style' => 'width: 75%;',
                )))
            ->add('subject')
            ->add('member', 'hidden_member')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberEmail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'memberemail';
    }
}
