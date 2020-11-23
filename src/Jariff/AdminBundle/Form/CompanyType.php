<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billToName')
            ->add('billToAdress')
            ->add('billToEmail')
            ->add('billToPhone')
            ->add('invoiceType')
            ->add('payment')
            ->add('shipToName')
            ->add('shipToAdress')
            ->add('shipToEmail')
            ->add('shipToPhone')
            ->add('name')
            ->add('accountManager')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\Company'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'company';
    }
}
