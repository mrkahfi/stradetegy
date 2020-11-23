<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\MXImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormMXImportType extends AbstractType
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

                        "hs_code"=>"HS Code",
                        "hs_product_dectiption"=>"HS Product Description",
                        "product_schedule_b_code"=>"Product Schedule B Code",
                        "product_decription_by_schedule_b_code_mexico"=>"Product Description By Schedule B Code Mexico",
                        "way_of_transport"=>"Way Of Transport",
                        "country_of_origin"=>"Country Of Origin",
                        "custom"=>"Custom"

                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'hs_product_dectiption'
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
