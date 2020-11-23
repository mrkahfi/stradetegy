<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormCLExportType extends AbstractType
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
                        "product" => "Product",
                        "variety" => "Variety",
                        "brand" => "Brand",
                        "description" => "Description",
                        "destiny_country" => "Destiny Country",
                        "transport_type" => "Transport Type",
                        "transport_company" => "Transport Company",
                        "ship_name" => "Ship Name",
                        "load_type" => "Load Type",
                        "origin_port" => "Origin Port",
                        "landing_port" => "Landing Port",
                        "us_fob" => "US FOB",
                        "us_freight" => "US Freight",
                        "us_insurance" => "US Insurance",
                        "us_cif" => "US CIF",
                        "us_fob_unit" => "US FOB Unit",
                        "package_type" => "Package Type",
                        "exporter_region" => "Exporter Region",
                        "sale_condition" => "Sale Condition",
                        "economic_zone" => "Economic Zone",
                        "incoterms" => "Incoterms"

                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'product'
                        : $this->preferredChoice),
                    'attr' => array('col_md' => 'col-md-2')
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
