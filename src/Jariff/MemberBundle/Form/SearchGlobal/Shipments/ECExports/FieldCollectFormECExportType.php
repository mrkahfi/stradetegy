<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormECExportType extends AbstractType
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
                        "refrendo"=>"Refrendo",
                        "declaration_number_dau"=>"Declaration Number Dau",
                        "destination_country"=>"Destiantion Country",
                        "loading_port"=>"Loading Port",
                        "transport_type"=>"Transport Type",
                        "comercial_description"=>"Commercial Description",
                        "condition"=>"Condition",
                        "container"=>"Container",
                        "packages"=>"Packages",
                        "consignee"=>"Consignee",
                        "customs_agent"=>"Custom Agent",
                        "customs_agency"=>"Customs Agency",
                        "shipping_agency"=>"Shipping Agency",
                        "ship"=>"Ship"

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
