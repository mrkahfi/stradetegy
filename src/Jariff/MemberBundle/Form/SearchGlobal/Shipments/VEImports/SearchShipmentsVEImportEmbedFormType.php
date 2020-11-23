<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\VEImports;

use Jariff\MemberBundle\Form\Demo\Field\FieldConditionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Jariff\MemberBundle\Form\SearchGlobal\Field\FieldQFormType;


class SearchShipmentsVEImportEmbedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('collect', 'collection',
                array(
                    'label_attr' => array('style' =>'display:none'),
                    'type' => new FieldCollectFormVEImportType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,

                ))
            ->add('q', 'collection',
                array(
                    'label_attr' => array('style' =>'display:none'),
                    'type' => new FieldQFormType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                ))
            ->add('condition', 'collection',
                array(
                    'label_attr' => array('style' =>'display:none'),
                    'type' => new FieldConditionFormType(),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add'    => true,
                ))

        ;


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
