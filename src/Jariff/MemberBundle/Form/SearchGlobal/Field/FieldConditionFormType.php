<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldConditionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('condition', 'choice',
            array(
                'label_attr' => array('class' => 'csr-only'),
                'choices' => array(
                    'and' => 'AND',
                    'or' => 'OR',
                    'not' => 'NOT',
                    ),
                'attr' => array('col_md' => 'col-md-1'),
                'required' => 'true',
                'data' => 'and'
                ));


        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Jariff\MemberBundle\Model\SearchFieldCondition'
                ));
        }


        public function getName()
        {
            return 'member_search_global_condition';
        }
    }
