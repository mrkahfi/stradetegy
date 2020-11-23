<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlertsSaveGlobalSearchFormType extends AbstractType
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
        return 'alert_save_search_global';
    }
}
