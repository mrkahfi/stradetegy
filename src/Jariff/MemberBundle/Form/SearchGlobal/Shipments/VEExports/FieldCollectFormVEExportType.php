<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormVEExportType extends AbstractType
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
                        "chapter"=>"Chapter",
                        "chapter_description"=>"Chapter Description",
                        "hs_code"=>"HS Code",
                        "payment"=>"Payment",
                        "hs_code_description"=>"HS Code Description",
                        "exporter"=>"Exporter",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "dest_country"=>"Dest Country",
                        "dest_port"=>"Dest Port",
                        "us_fob_bolivare"=>"US FOB Bolivare",
                        "us_fob"=>"US FOB"
                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'exporter'
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
