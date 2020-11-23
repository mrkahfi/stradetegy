<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal;

use Jariff\MemberBundle\Form\Demo\Field\FieldConditionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\MemberBundle\Form\SearchGlobal\Shipments\FieldCollectFormType;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldQFormType;


class SearchShipmentsEmbedFormType extends AbstractType
{

    private $preferedChoice;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
           ->add('collect', 'collection',
                array(
                    'label_attr' => array('class' =>'sr-only'),
                    'type' => $this->preferedChoice == null ? new FieldCollectFormType() : FieldCollectFormType::withPreferedChoice($this->preferedChoice),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                    'required'    => true

                ))
            ->add('q', 'collection',
                array(
                    'label_attr' => array('class' =>'sr-only'),
                    'type' => new FieldQFormType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                    'required'    => true,
                ))
            ->add('condition', 'collection',
                array(
                    'label_attr' => array('class' =>'sr-only'),
                    'type' => new FieldConditionFormType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                    'required'    => true,
                ))

        ;


    }

    public function __construct() {
    }

    public static function withPreferedChoice($param) 
    {
        $instance = new self();
        $instance->preferedChoice = $param;
        return $instance;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchEmbed'
        ));
    }


    public function getName()
    {
        return 'demo_search_shipments_embed';
    }
}
