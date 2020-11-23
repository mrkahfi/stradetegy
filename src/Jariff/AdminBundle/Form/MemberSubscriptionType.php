<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberSubscriptionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('everythingPlan', 'hidden')
            ->add('history', 'subscription_history')
            ->add('download', 'subscription_download')
            ->add('bigPicture', 'subscription_bigPicture')
            ->add('search', 'subscription_search')
            ->add('training', 'choice', array(
                    'empty_value' => '---',
                    'choices' => array(
                        'Not Yet Offered' => 'Not Yet Offered',
                        'Offered'         => 'Offered',
                        'Declined'        => 'Declined',
                        'Complete'        => 'Complete',
                    )
                ))
            ->add('customDiscount')
            ->add('member', 'hidden_member')
            ->add('owner')
            ->add('sales2')
            ->add('paymentTerm', 'subscription_term')
            ->add('month', 'subscription_month')
        ;
        // ->add('dateActivation')
        // ->add('cancelDate')
        // ->add('dateSuspended')
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberSubscription'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'subscription';
    }
}
