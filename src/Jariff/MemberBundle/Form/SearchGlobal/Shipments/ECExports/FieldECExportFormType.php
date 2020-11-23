<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\ECExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldECExportFormType extends AbstractType
{    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
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
                    'multiple' => true,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\FieldUsCustom'
        ));
    }


    public function getName()
    {
        return 'field_us_custom';
    }
}
