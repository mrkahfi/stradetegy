<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldArImportFormType extends AbstractType
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
                        "import_id"=>"Import ID",
                        "operation_type"=>"Operation Type",
                        "custom"=>"Custom",
                        "consignee_name"=>"Consignee Name",
                        "importer_id"=>"Importer ID",
                        "adq_country"=>"ADQ Country",
                        "type_of_transport"=>"Type Of Transport",
                        "embarq_port"=>"Embarq Port",
                        "incoterms"=>"Incoterms",
                        "total_fob"=>"Total FOB",
                        "freight_us"=>"Freight US",
                        "insurance_us"=>"Insurance US",
                        "total_cif"=>"Total CIF",
                        "number_of_packages"=>"Number Of Packages",
                        "gross_weight"=>"Gross Weight",
                        "weight_unit"=>"Weight Unit",
                        "item_number"=>"Item Number",
                        "orig_country"=>"Origin Country",
                        "commercial_quantity"=>"Commercial Quantity",
                        "commercial_unit"=>"Commercial Unit",
                        "fob_item"=>"FOB Item",
                        "freight_item"=>"Freight Item",
                        "insurance_item"=>"Insurance Item",
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
