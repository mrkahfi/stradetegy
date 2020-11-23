<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormCAImportType extends AbstractType
{
    private $preferredChoice;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('collect', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'choices' => array(
                    'all' => 'All',
                        // "product" => "Product",
                        // "brand" => "Brand",
                    "hs_code" => "HS Code",
                    "spanish_description" => "Spanish Description",
                    "province" => "Province",
                    "tipes" => "Tipes",
                    "origin_country" => "Origin Country",
                    "state_of_origin" => "State Of Origin",
                    "quantity" => "Quantity",
                    "comercial_unit" => "Comercial Unit",
                    "import_fob_value" => "Import FOB Value",
                    "port_of_entry" => "Port Of Entry",
                    "hs_code_description" => "HS Code Description",

                    ),
                'attr' => array('col_md' => 'col-md-2'),
                'preferred_choices' => array($this->preferredChoice == null ? 'hs_code_description'
                    : $this->preferredChoice),
                ));


}

public static function withPreferedChoice($param)
{
    $instance = new self();
    $instance->preferredChoice = $param;
    return $instance;
}

public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'Jariff\MemberBundle\Model\SearchFieldCollect'
        ));
}


public function getName()
{
    return 'demo_search_collect';
}
}
