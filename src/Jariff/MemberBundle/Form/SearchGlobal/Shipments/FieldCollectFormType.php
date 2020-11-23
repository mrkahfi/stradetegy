<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormType extends AbstractType
{

    private $preferredChoice;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collect', 'choice',
                array(
                    'label_attr' => array('class' => 'sr-only'),
                    'choices' => array(
                        'product_desc' => 'Product',
                        'consignee_name' => 'Consignee',
                        'consignee_address' => 'Consignee Address or ZIP Code',
                        'shipper_name' => 'Shipper Name',
                        'shipper_address' => 'Shipper Address',
                        'notify_party_names' => 'Notify Party',
                        'all' => 'All',
                        'loading_port' => 'Foreign Port',
                        'bill_of_lading' => 'Bill Of Lading',
                        'vessel_name' => 'Vessel Name',
                        'country' => 'Country Of Origin',
                        'marks_numbers' => 'Marks & Numbers',
                        'carrier_sasc_code' => 'Carrier Code',
                        "container_id" => "Container Number",
                        "unloading_port" => "US Port"
                    ),
                    'required'    => true,
                    'attr' =>array('col_md' => 'col-md-3'),
                    'preferred_choices' => array($this->preferredChoice == null ? 'product_desc' 
                        : $this->preferredChoice)
                ));


    }

    // public function __construct() {
    //     $this->preferredChoice = 'all';
    // }

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
