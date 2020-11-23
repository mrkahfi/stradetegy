<?php 

namespace Jariff\AdminBundle\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscriptionPaymentTermType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => true,
            'label'    => 'Payment Term',
            'choices'  => array(
                'mtm' => 'Month to Month',
                'pif' => 'Paid in full',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    { 
        return 'subscription_term';
    }
}