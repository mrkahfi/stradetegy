<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldShipperNameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['dataShipperName'] as $dc) {
            if (isset($dc['top_shipper']['hits']['hits'][0]['_source']['shipper_name'])) {
                $choices[$dc['key']] = strtoupper(strtoupper($dc['top_shipper']['hits']['hits'][0]['_source']['shipper_name'])) . '|' . $dc['doc_count'];

            }else{
                $choices[$dc['key']] = strtoupper(strtoupper($dc['key'])) . '|' . $dc['doc_count'];
            }
        }

        $builder
            ->add('shipper_name', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'attr' => array('class' => 'col-md-8'),
                    'multiple' => true,
                    'choices' => $choices,
                    'expanded' => true,
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\ShipperName',
            'dataShipperName' => null
        ));
    }


    public function getName()
    {
        return 'field_shipper_name';
    }
}
