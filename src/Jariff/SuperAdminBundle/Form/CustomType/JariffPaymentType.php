<?php 

namespace Jariff\AdminBundle\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JariffPaymentType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => '---',
            'choices'     => array(
                'cc'       => 'Credit Card',  
                'paypal'   => 'PayPal',  
                'check'    => 'Check',  
                'bankwire' => 'Bankwire',
            )));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'jariff_payment';
    }
}