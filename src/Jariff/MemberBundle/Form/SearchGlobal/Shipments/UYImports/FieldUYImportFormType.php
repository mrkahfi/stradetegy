<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\UYImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldUYImportFormType extends AbstractType
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
                        "importer"=>"Importer",
                        "importer_id"=>"Importer ID",
                        "hs_code"=>"HS CODE",
                        "product"=>"Product",
                        "country_of_origin"=>"Country Of Origin",
                        "country_of_acquisition"=>"Country of Acquisition",
                        "type_of_transport"=>"Type Of Transport",
                        "transportation_company"=>"Transportation Company",
                        "gross_weight"=>"Gross Weight",
                        "net_weight"=>"Net Weight",
                        "tax"=>"Tax",
                        "invalid_field"=>"Invalid Field",
                        "quantity"=>"Quantity",
                        "unit"=>"Unit",
                        "physical_units_quantity"=>"Physical Units Quantity",
                        "physical_units"=>"Physical Units",
                        "insurance_currency_of_origin"=>"Insurance Currency Of Origin",
                        "currency_of_insurance"=>"Currency Of Insurance",
                        "freight_currency_of_origin"=>"Freight Currency Of Origin",
                        "currency_of_freight"=>"Currency Of Freight",
                        "us_cif"=>"US CIF",
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
