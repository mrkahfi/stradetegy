<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldArExportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4','col_md' => 'col','style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "export_id"=>"Export ID",
                        "operation_type"=>"Operation Type",
                        "custom"=>"Custom",
                        "country_destination"=>"Country Destination",
                        "type_of_transport"=>"Type Of Transport",
                        "incoterms"=>"Incoterms",
                        "total_fob"=>"Total FOB",
                        "total_cif"=>"Total CIF",
                        "number_of_packages"=>"Number Of Packages",
                        "gross_weight"=>"Gross Weight",
                        "weight_unit"=>"Weight Unit",
                        "item_number"=>"Item Number",
                        "commercial_quantity"=>"Commercial Quantity",
                        "commercial_unit"=>"Commercial Unit",
                        "fob_item"=>"FOB Item",
                        "hs_code"=>"HS Code",
                        "product"=>"Product",
                        "cif_item"=>"CIF Item",
                        "subitem_number"=>"Subitem Number",
                        "brand"=>"Brand",
                        "variety"=>"Variety",
                        "attributes"=>"Attributes",
                        "us_fob_subitem"=>"US FOB Subitem",
                        "quantity_subitem"=>"Quantity Subitem"
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
