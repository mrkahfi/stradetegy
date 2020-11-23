<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\UYExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormUYExportType extends AbstractType
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
                        "custom"=>"Custom",
                        "exporter"=>"Exporter",
                        "exporter_id"=>"Exporter ID",
                        "hs_code"=>"HS CODE",
                        "product"=>"Product",
                        "country_destination"=>"Country Destination",
                        "type_of_transport"=>"Type Of Transport",
                        "transportation_company"=>"Transportation Company",
                        "physical_units_quantity"=>"Physical Units Quantity",
                        "physical_units"=>"Physical Units",
                        "us_fob"=>"US FOB",
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
