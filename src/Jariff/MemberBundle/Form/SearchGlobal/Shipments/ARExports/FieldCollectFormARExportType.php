<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormARExportType extends AbstractType
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
                        'product' => 'Product',
                        "operation_type"=>"Operation Type",
                        "custom"=>"Custom",
                        "country_destination"=>"Country Destination",
                        "type_of_transport"=>"Type Of Transport",
                        "incoterms"=>"Incoterms",
                        "commercial_unit"=>"Commercial Unit",
                        "brand"=>"Brand",
                        "variety"=>"Variety",
                        "attributes"=>"Attributes"

                    ),
                    'attr' => array('col_md' => 'col-md-2'),
                    'preferred_choices' => array($this->preferredChoice == null ? 'product'
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
