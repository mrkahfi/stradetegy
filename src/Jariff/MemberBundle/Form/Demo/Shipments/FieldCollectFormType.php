<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\Demo\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collect', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'choices' => array(
                        'all' => 'All',
                        'consignee_name' => 'Importer Name',
                        'consignee_address' => 'Importer Address',
                        'shipper_name' => 'Exporter Name',
                        'shipper_address' => 'Exporter Address',
                        'product_desc' => 'Product',
                        'notify_party_names' => 'Notify Party Names',
                        'notify_party_address' => 'Notify Party Address',
                        'bill_of_lading' => 'Bill Of Lading',
                        'master_bill_of_lading' => 'Master Bill Of Lading',
                        'container_id' => 'Container Number',
                        'place_of_receipt' => 'Place Of Receipt',
                        'vessel_name' => 'Vessel Name',
                        'carrier_sasc_code' => 'Carrier SASC Code',
                    ),
                    'attr' => array('class' => 'form-control col-md-4')
                ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchFieldCollect'
        ));
    }


    public function getName()
    {
        return 'demo_search';
    }
}
