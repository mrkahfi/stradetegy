<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\UYExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldUYExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "custom"=>"Custom",
                        "exporter"=>"Exporter",
                        "exporter_id"=>"Exporter ID",
                        "hs_code"=>"HS CODE",
                        "product"=>"Product",
                        "country_destination"=>"Country Destination",
                        "type_of_transport"=>"Type Of Transport",
                        "transportation_company"=>"Transportation Company",
                        "gross_weight"=>"Gross Weight",
                        "net_weight"=>"Net Weight",
                        "quantity"=>"Quantity",
                        "unit"=>"Unit",
                        "physical_units_quantity"=>"Physical Units Quantity",
                        "physical_units"=>"Physical Units",
                        "us_fob"=>"US FOB",
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
