<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaveSearchShipmentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_of_search', null,
                array(
                    'label' => 'Name Of Search',
                    'attr' => array('class' => 'col-md-12 form-control')
                ))
            ->add('description', null,
                array(
                    'label' => 'Description (Optional)',
                    'attr' => array('class' => 'col-md-12 form-control')
                ))
            ->add('slug_country_subscription', 'hidden',
                array(
                    'label' => 'Description (Optional)',
                    'attr' => array('class' => 'col-md-12 form-control')
                ))
            ->add('is_alert', 'checkbox', array(
                'label'     => 'Enable Alert',
                'required'  => false,
                'label_attr' => array('class' => 'col-md-12'),
                'attr' => array('checked' => 'checked','col_md' => 'col-md-12')
            ))
        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\SavedSearch'
        ));
    }


    public function getName()
    {
        return 'save_search_shipments';
    }
}
