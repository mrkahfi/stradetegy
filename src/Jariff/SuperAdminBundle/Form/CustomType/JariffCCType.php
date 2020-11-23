<?php 

namespace Jariff\AdminBundle\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JariffCCType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => '---',
            'choices'     => array(
                'mastercard'       => 'MasterCard',          
                'visa'             => 'Visa',                        
                'american express' => 'American Express',
                'discover'         => 'Discover',                
            )));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'jariff_cc';
    }
}