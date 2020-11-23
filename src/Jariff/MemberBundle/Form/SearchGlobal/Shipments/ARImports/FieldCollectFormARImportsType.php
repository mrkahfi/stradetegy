<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ARImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormARImportsType extends AbstractType
{
    private $preferredChoice;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collect', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'choices' => array(
                        "product" => 'Product',
                        "consignee_name" => 'Consignee',
                        "orig_country" => "Country Of Origin",
                        'all' => 'All',
                        "hs_code" => "HS Code",
                        "embarq_port" => "Embarkation",
                        "type_of_transport" => "Transporation Type",
                        "brand" => "Brand",

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
