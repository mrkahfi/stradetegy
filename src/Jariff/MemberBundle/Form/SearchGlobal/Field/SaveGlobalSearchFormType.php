<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaveGlobalSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,
                array(
                    'label' => 'Name Of Search',
                    'attr' => array('class' => 'col-md-12')
                ))
            ->add('description', null,
                array(
                    'label' => 'Description (Optional)',
                    'attr' => array('class' => 'col-md-12')
                ))
            ->add('is_alerts', 'checkbox', array(
                'label'     => 'Enable email alerts for this search',
                'required'  => false,
                'label_attr' => array('class' => 'col-md-3'),
                'attr' => array('class' => 'col-md-1','checked' => 'checked')
            ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\DocumentBundle\Document\AlertsSaveGlobalSearch'
        ));
    }


    public function getName()
    {
        return 'save_search_global';
    }
}
