<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Jariff\AdminBundle\Form\Subscriber\MemberAttachmentSubscriber;

class MemberAttachmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('attachmentFile', 'file', array())
            ->add('member', 'hidden_member')
            ->addEventSubscriber(new MemberAttachmentSubscriber())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\MemberAttachment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'member_attachment';
    }
}
