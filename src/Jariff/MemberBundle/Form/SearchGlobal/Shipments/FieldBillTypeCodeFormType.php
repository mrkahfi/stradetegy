<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldBillTypeCodeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();

        if (!empty($options['dataBillTypeCode'])) {
            foreach ($options['dataBillTypeCode'] as $dc) {
                $choices[strtoupper($dc['key'])] = strtoupper($dc['key']) . '|' . $dc['doc_count'];
            }
        }


        $builder
            ->add('bill_type_code', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('class' => 'col-md-2'),
                    'multiple' => true,
                    'choices' => $choices,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\BillTypeCode',
            'dataBillTypeCode' => null
        ));
    }


    public function getName()
    {
        return 'field_bill_type_code';
    }
}
