<?php

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberSettingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language','choice', array(
                'choices' => array(
                    // 'ID' => 'Indonesian',
                    'EN' => 'English',
                    'ES' => 'Spanish',
                    'FR' => 'French',
                    'CN' => 'Chinese',
                ),
                'label' => 'Language',
            ))
            ->add('currency','choice', array(
                'choices' => array(
                    'IDR' => 'Indonesian Rupiah',
                    'USD' => 'US Dollar',
                    'EUR' => 'Euro',

                ),
                'label' => 'Currency',
            ))
            ->add('weight','choice', array(
                'choices' => array(
                    'KG' => 'Kilogram (Kg)',
                    'LB' => 'POUNDS (lb)',
                ),
                'label' => 'Gross Weight',
            ))
            ->add('distance','choice', array(
                'choices' => array(
                    'KM' => 'Kilometer',
                    'MIL' => 'MIL',
                ),
                'label' => 'Distance',
            ))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberSetting'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jariff_memberbundle_membersetting';
    }
}
