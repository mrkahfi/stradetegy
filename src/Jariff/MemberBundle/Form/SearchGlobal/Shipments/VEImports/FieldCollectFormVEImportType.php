<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormVEImportType extends AbstractType
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
                        "bl"=>"BL",
                        "chapter"=>"Chapter",
                        "chapter_description"=>"Chapter Description",
                        "hs_code"=>"HS Code",
                        "hs_code_description"=>"HS Code Description",
                        "importer"=>"Importer",
                        "custom"=>"Custom",
                        "transport_type"=>"Transport Type",
                        "embarq_port"=>"Embarq Port",
                        "origin_country"=>"Origin Country",
                        "adq_country"=>"ADQ Country",
                        "us_fob_bolivares"=>"US FOB Bolivares",
                        "us_fob"=>"US FOB",
                        "us_cif_bolivares"=>"US CIF Bolivares",
                        "us_cif"=>"US CIF"
                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'importer'
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
