<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PEExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormPEExportType extends AbstractType
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
                        "serie" => "Serie",
                        "custom" => "Custom",
                        "exporter" => "Exporter",
                        "exporter_address" => "Exporter Address",
                        "exporter_department" => "Exporter Department",
                        "exporter_state" => "Exporter State",
                        "exporter_district" => "Exporter District",
                        "exporter_phone" => "Exporter Phone",
                        "exporter_fax" => "Exporter Fax",
                        "hs_code" => "HS Code",
                        "hs_code_description" => "HS Code Description",
                        "cargo_description" => "Cargo Description",
                        "us_fob" => "US FOB",
                        "commercial_quantity" => "Commercial Quantity",
                        "commercial_measure_unit" => "Commercial Measure Unit",
                        "transport_type" => "Transport Type",
                        "bank" => "Bank",
                        "destination_country" => "Desctination Country",
                        "destination_port" => "Destination Port",
                        "customs_agent" => "Customs Agent",
                        "transport_company" => "Transport Company",
                        "ship" => "Ship"
                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'cargo_description'
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
