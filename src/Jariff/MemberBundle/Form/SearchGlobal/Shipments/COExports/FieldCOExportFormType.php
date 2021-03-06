<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\COExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCOExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        'all' => 'All',

                        "exporter"=>"Exporter",
                        "exporter_address"=>"Exporter Address",
                        "department_destination"=>"Department Destination",
                        "hs_code"=>"HS Code",
                        "country_destination"=>"Country Destination",
                        "type_of_transport"=>"Type Of Transport",
                        "method_of_payment"=>"Method Of Payment",
                        "importer"=>"Importer",
                        "importer_address"=>"Importer Address"

                    ),
                    'multiple' => true,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\FieldUsCustom'
        ));
    }


    public function getName()
    {
        return 'field_us_custom';
    }
}
