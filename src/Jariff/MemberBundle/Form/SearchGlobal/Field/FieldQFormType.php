<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldQFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', null,
                array(
                    'label_attr' => array('class' => 'sr-only'),
                    'attr' => array('placeholder' => 'Enter a keyword','col_md' => 'col-md-6'),
                    'required' => 'true'
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchFieldQ'
        ));
    }


    public function getName()
    {
        return 'member_search_global_q';
    }
}
