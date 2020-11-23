<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_from', 'text',
                array(
                    'label' => 'From',
                    'attr' =>array('placeholder' => 'Start Date','class' => 'form-control')
                ))
            ->add('date_to', 'text',
                array(
                    'label' => 'To',
                    'attr' =>array('placeholder' => 'End Date','class' => 'form-control')

                ))
            ->add('marked_master', 'choice',
                array(

                    'choices' => array(
                        'M' => 'Master Only',
                        'H' => 'House Only'
                    ),
                    'expanded' => true,
                    'multiple' => true,


                ))
            // ->add('not_valid_date', 'checkbox',
            //     array(

            //         'attr' =>array('col_md' => 'col-md-12','tooltips' => 'Results will be shown in under valid date results '),
            //         'label' => 'Include records with invalid dates',
            //         'required'    => false
            //     ))
        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\DateRange'
        ));
    }


    public function getName()
    {
        return 'demo_search';
    }
}

//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\FormBuilderInterface;
//
//use Symfony\Component\OptionsResolver\Options;
//use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//
//use Jariff\MemberBundle\Form\SearchGlobal\DataTransformer\DateRangeViewTransformer;
//use Jariff\MemberBundle\Form\SearchGlobal\Validator\DateRangeValidator;
//
//class DateRangeFormType extends AbstractType
//{
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        $builder
//            ->add('start_date', 'date', array_merge_recursive(array(
//                'property_path' => 'start',
//                'widget' => 'single_text',
//                'format' => 'yyyy-MM-dd',
//                'model_timezone' => 'UTC',
//                'view_timezone' => 'UTC',
//                'attr' => array(
//                    'data-type' => 'start',
//                ),
//            ), $options['start_options']))
//            ->add('end_date', 'date', array_merge_recursive(array(
//                'property_path' => 'end',
//                'widget' => 'single_text',
//                'format' => 'yyyy-MM-dd',
//                'model_timezone' => 'UTC',
//                'view_timezone' => 'UTC',
//                'attr' => array(
//                    'data-type' => 'end',
//                ),
//            ), $options['end_options']))
//        ;
//
//        $builder->addViewTransformer($options['transformer']);
//        $builder->addEventSubscriber($options['validator']);
//    }
//
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Jariff\MemberBundle\Model\DateRange',
//            'end_options' => array(),
//            'start_options' => array(),
//            'transformer' => null,
//            'validator' => null,
//        ));
//
//        $resolver->setAllowedTypes(array(
//            'transformer' => 'Symfony\Component\Form\DataTransformerInterface',
//            'validator' => 'Symfony\Component\EventDispatcher\EventSubscriberInterface',
//        ));
//
//        // Those normalizers lazily create the required objects, if none given.
//        $resolver->setNormalizers(array(
//            'transformer' => function (Options $options, $value) {
//                    if (!$value) {
//                        $value = new DateRangeViewTransformer(new OptionsResolver());
//                    }
//
//                    return $value;
//                },
//            'validator' => function (Options $options, $value) {
//                    if (!$value) {
//                        $value = new DateRangeValidator(new OptionsResolver());
//                    }
//
//                    return $value;
//                },
//        ));
//    }
//
//    public function getName()
//    {
//        return 'date_range';
//    }
//}