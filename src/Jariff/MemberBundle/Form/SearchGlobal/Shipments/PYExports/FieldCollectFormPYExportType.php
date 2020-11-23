<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PYExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormPYExportType extends AbstractType
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

                        "exporter"=>"Exporter",
                        "consignee"=>"Consignee",
                        "destiny_country"=>"Destiny Country",
                        "hs_code"=>"HS Code",
                        "quantity"=>"Quantity",
                        "measure_unit"=>"Measure Unit",
                        "product"=>"Product",

                        "us_fob"=>"US FOB",
                        "us_freight"=>"US Freight",
                        "us_insurance"=>"US Insurance",
                        "us_cif"=>"US CIF",
                        "us_fob_unit"=>"US FOB Unit",
                        "brand"=>"Brand",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "transport_company"=>"Transport Company",
                        "transportist_country"=>"Transportist Country",
                        "manifest"=>"Manifest"
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
