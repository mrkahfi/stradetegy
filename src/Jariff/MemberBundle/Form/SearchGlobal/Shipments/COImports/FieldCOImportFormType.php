<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\COImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCOImportFormType extends AbstractType
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
                        "control_id"=>"Control ID",
                        "importer_id"=>"Importer ID",
                        "importer"=>"Importer",
                        "importer_address"=>"Importer Address",
                        "importer_phone"=>"Importer Phone",
                        "department_destination"=>"Department Destination",
                        "hs_code"=>"HS Code",
                        "product"=>"Product",
                        "country_of_origin"=>"Country Of Origin",
                        "country_of_acquisition"=>"Country Of Acquisition",
                        "type_of_transport"=>"Type Of Transport",
                        "method_of_payment"=>"Method Of Payment",
                        "transportation_company"=>"Transportation Company",
                        "weight"=>"Weight",
                        "tax"=>"Tax",
                        "exporter"=>"Exporter",
                        "exporter_address"=>"Exporter Address",
                        "exporter_city"=>"Exporter City",
                        "exporter_country"=>"Exporter Country",
                        "exporter_phone_email"=>"Exporter Phone Email",
                        "quantity"=>"Quantity",
                        "unit_of_measure"=>"Unit Of Measure",
                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF"
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
