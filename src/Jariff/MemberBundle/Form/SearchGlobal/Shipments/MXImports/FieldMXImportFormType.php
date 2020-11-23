<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\MXImports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldMXImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_us_custom', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('col_md_ch' => 'col-md-4', 'col_md' => 'col', 'style' => 'margin: 0px 3px 3px 3px;', 'class' => ''),
                    'choices' => array(
                        "date"=>"Date",
                        "hs_code"=>"HS Code",
                        "hs_product_dectiption"=>"HS Product Description",
                        "product_schedule_b_code"=>"Product Schedule B Code",
                        "product_decription_by_schedule_b_code_mexico"=>"Product Description By Schedule B Code Mexico",
                        "way_of_transport"=>"Way Of Transport",
                        "country_of_origin"=>"Country Of Origin",
                        "custom"=>"Custom",
                        "total_fob_value"=>"Total FOB Value",
                        "total_quantity_1"=>"Total Quantity 1",
                        "measure_unit_1"=>"Measure Unit 1",
                        "fob_value_per_unit"=>"FOB Value Per Unit",
                        "total_gross_weight_kg"=>"Total Gross Weight Kg",
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
