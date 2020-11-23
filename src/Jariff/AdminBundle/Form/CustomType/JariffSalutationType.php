<?php 

namespace Jariff\AdminBundle\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JariffSalutationType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => '---',
            'choices'     => array(
                'Mr.'  => 'Mr.',  
                'Mrs.' => 'Mrs.', 
                'Ms.'  => 'Ms.',
            )));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'jariff_salutation';
    }
}