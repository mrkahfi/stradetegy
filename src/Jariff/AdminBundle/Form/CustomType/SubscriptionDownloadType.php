<?php 

namespace Jariff\AdminBundle\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscriptionDownloadType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $entities = $this->om->getRepository('JariffAdminBundle:Subscription')
            ->createQueryBuilder('s')
            ->select('s')
            ->where('s.category = \'download\'')
            ->getQuery()->getResult();

        $choices = array();
        foreach ($entities as $entity) {
            $choices[$entity->getPrice()] = $entity->getLabel();
        }

        $resolver->setDefaults(array(
            'required'    => true,
            'choices'     => $choices
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'subscription_download';
    }
}