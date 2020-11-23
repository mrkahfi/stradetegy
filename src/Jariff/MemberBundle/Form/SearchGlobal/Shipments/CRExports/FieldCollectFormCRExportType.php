<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\Shipments\CRExports;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldCollectFormCRExportType extends AbstractType
{
    private $preferredChoice;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collect', 'choice',
                array(
                    'label_attr' => array('style' => 'display:none'),
                    'choices' => array(
                        'all' => 'All',
                        "declaration"=>"Declaration",
                        "tipe"=>"Type",
                        "exporter"=>"Exporter",
                        "exporter_address"=>"Exporter Address",
                        "transport_type"=>"Transport Type",
                        "cargo_description"=>"Cargo Destination",
                        "packages"=>"Packages",
                        "packages_type"=>"Packages Type",
                        "brand"=>"Brand"


                    ),
                    'preferred_choices' => array($this->preferredChoice == null ? 'product'
                        : $this->preferredChoice),
                    'attr' => array('col_md' => 'col-md-2')
                ));

  }



    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Model\SearchFieldCollect'
        ));
    }


    public function getName()
    {
        return 'demo_search_collect';
    }
}
