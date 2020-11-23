<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CAExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormCAExportType extends AbstractType
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
                        "hs_code" => "HS Code",
                        "spanish_description" => "Spanish Description",
                        "province" => "Province",
                        "tipes" => "Tipes",
                        "destiny_country" => "Destiny Country",
                        "destiny_state" => "Destiny State",
                        "comercial_unit" => "Comercial Unit",
                        "export_fob_value" => "Export FOB Value",
                        "port_of_departure" => "Port Of Departure",
                        "hs_code_description" => "HS Code Description",
                        "export_type" => "Export Type",

                    ),
                    'attr' => array('col_md' => 'col-md-2'),
                    'preferred_choices' => array($this->preferredChoice == null ? 'hs_code'
                        : $this->preferredChoice)
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
