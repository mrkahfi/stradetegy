<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CLImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormCLImportsType extends AbstractType
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

                        "importer"=>"Consignee",
                        "hs_code"=>"HS Code",
                        "hs_code_description"=>"HS Code Description",
                        "product"=>"Product",

                        "origin_country"=>"Origin Country",
                        // "origin_port"=>"Origin Port",
                        // "landing_port"=>"Landing Port",



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
