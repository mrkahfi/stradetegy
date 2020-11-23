<?php

namespace Jariff\AdminBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ilmoe\BrowseBundle\Entity\Location;

class MemberToIdTransformer implements DataTransformerInterface
{
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Location) to a string (number).
     *
     * @param  Location|null $Location
     * @return string
     */
    public function transform($member)
    {
        if (null === $member) {
            return '';
        }
        return $member->getId();
    }

    /**
     * Transforms a id to an object (member).
     *
     * @param  int $id
     * @return member|null
     * @throws TransformationFailedException if object (member) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $member = $this->om->getRepository('JariffMemberBundle:Member')->find($id);

        if (null === $member) {
            throw new TransformationFailedException('Member does not exist!');
        }

        return $member;
    }
}
