<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\JNPTImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldJNPTImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date" => "Date",
                        "hs_code" => "HS Code",
                        "importer_id" => "Importer ID",
                        "importer_name" => "Importer Name",
                        "importer_address" => "Importer Address",
                        "importer_address_2" => "Importer Address 2",
                        "city" => "City",
                        "zip_cod" => "Zip Code",
                        "phone" => "Phone",
                        "fax" => "FAX",
                        "e_mail" => "Email",
                        "item_description" => "Item Description",
                        "quantity" => "Quantity",
                        "unit" => "Unit",
                        "unit_price_invoice" => "Unit Price Invoice",
                        "unit_price_usd" => "Unit Price USD",
                        "unit_price_euro" => "Unit Price UERO",
                        "custom_agent" => "Custom Agent",
                        "supplier_name" => "Supplier Name",
                        "supplier_address" => "Supplier Address",
                        "supplier_country" => "Supplier Country",
                        "origin_port" => "Origin Port",
                        "contact_1" => "Contact 1",
                        "contact_2" => "Contact 2",
                        "bank" => "Bank",
                        "be_no" => "Be No."
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
