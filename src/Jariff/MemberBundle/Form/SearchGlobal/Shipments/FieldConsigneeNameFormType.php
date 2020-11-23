<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldConsigneeNameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['dataConsigneeName'] as $dc) {
            if (isset($dc['top_consignee']['hits']['hits'][0]['_source']['consignee_name'])){
                $choices[$dc['key']] = strtoupper(strtoupper($dc['top_consignee']['hits']['hits'][0]['_source']['consignee_name'])) . '|' . $dc['doc_count'];
            }
            else{
                $choices[$dc['key']] = strtoupper(strtoupper($dc['key'])) . '|' . $dc['doc_count'];
            }


        }

        $builder
            ->add('consignee_name', 'choice',
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
            'data_class' => 'Jariff\MemberBundle\Model\ConsigneeName',
            'dataConsigneeName' => null
        ));
    }


    public function getName()
    {
        return 'field_consignee_name';
    }
}
