<?php

namespace Jariff\MemberBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PendingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('everythingPlan', 'hidden', array())
            ->add('customPlan', 'hidden', array())
            ->add('history', 'subscription_history')
            ->add('search', 'subscription_search')
            ->add('download', 'subscription_download')
            ->add('bigPicture', 'subscription_bigPicture')
            ->add('paymentTerm', 'subscription_term')
            ->add('month', 'subscription_month')            
            ->add('email', 'text', array(
                    'label' => 'Email/Login',
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('phone', 'text', array(
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('salutation', 'jariff_salutation', array(
                    'attr' => array(
                        'style' => 'width: 54px;',
                    ),
                ))
            ->add('firstName', 'text', array(
                    'attr' => array(
                        'style'       => 'width: 200px;',
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('lastName', 'text', array(
                    'attr' => array(
                        'style'       => 'width: 180px;',
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('password', 'password', array(
                    'attr' => array(
                        'class'       => 'tooltip22',
                    ),
                ))
            ->add('companyName', 'text', array(
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('country', 'jariff_country', array(
                'label' => 'What country do you live in?',
                'preferred_choices' => array(
                    "United States"  => "United States",
                ),
                'attr' => array(
                    'class' => 'select2',
                    'style' => 'width: 75%;',
                )))
            ->add('payment', 'jariff_payment', array(
                'label' => 'Payment type',
                'attr' => array(
                    'style' => 'width: 200px;'
                )))
            ->add('ccType', 'jariff_cc', array(
                'required' => false,
                'label' => 'Credit card type',
                'attr' => array(
                    'style' => 'width: 200px;'
                )))
            ->add('zipToBill', 'text', array(
                    'label' => 'Postal/Zip Code',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 200px;',
                        'class' => 'tooltip22',
                        'title' => 'Postal/Zip Code of credit card billing address'
                    ),
                ))
            ->add('number', 'text', array(
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 200px;',
                        'class' => 'tooltip22',
                        'title' => 'Just type numeric input. AmEx credit card must 15 digit length. Visa, MasterCard, and Discover must 16 digit length.'
                    ),
                ))
            ->add('secureCode', 'text', array(
                    'required' => false,
                    'label' => 'Security Code',
                    'attr' => array(
                        'style' => 'width: 200px;',
                        'class' => 'tooltip22',
                        'title' => '<img width="250px" src="/bundles/jariffproject/frontend/images/creditcardsecurity.gif"/>'
                    ),
                ))
            ->add('expired', 'date', array(
                'required'    => false,
                'label'       => 'Expiration Date',
                'widget'      => 'choice',
                'years'       => range(date('Y'), date('Y')+12),
                'days'        => array(1),
                'empty_value' => array('year' => false, 'month' => false, 'day' => false),
            ))
            ->add('term', 'checkbox', array(
                'label' => 'Terms and Conditions',
                'mapped' => false
            ));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Jariff\MemberBundle\Entity\Pending',
            'validation_groups' => array('registration'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pending';
    }
}
