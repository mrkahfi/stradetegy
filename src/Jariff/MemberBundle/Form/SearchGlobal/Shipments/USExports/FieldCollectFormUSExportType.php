<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\USExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormUSExportType extends AbstractType
{
    private $preferredChoice;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('collect', 'choice',
            array(
                'label_attr' => array('style' => 'display:none'),
                'choices' => array(
                    "shipper_name"=>"Shipper",
                    "shipper_full_address"=>"Shipper Address",
                    "bill_of_lading_number"=>"Bill Of Lading Number",
                    "container_number"=>"Container Number",
                    'all' => 'All',
                    "vessel_name"=>"Vessel Name",
                    "destination"=>"Destination Country",
                    "item_description"=>"Product "
                    ),
                'attr' => array('col_md' => 'col-md-2'),
                'preferred_choices' => array($this->preferredChoice == null ? 'item_description'
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
