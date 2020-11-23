<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\PAExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormPAExportType extends AbstractType
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

                        "ruc_exporter_id"=>"Ruc Exporter ID",
                        "exporter"=>"Exporter",
                        "hscode"=>"HS Code",
                        "product_description"=>"Product Description",
                        "customs_zone"=>"Customs Zone",
                        "customs_name"=>"Customs Name",
                        "transport_type"=>"Transport Type",
                        "declaration_number"=>"Declaration Number",
                        "destiny_country"=>"Desctiny Country",
                        "packages"=>"Packages",
                        "measure_unit"=>"Measure",
                        "us_fob"=>"US FOB"

                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'product_description'
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
