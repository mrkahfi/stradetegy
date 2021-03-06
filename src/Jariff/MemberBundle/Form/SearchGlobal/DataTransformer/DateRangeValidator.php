<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Form\SearchGlobal\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Jariff\MemberBundle\Model\DateRange;

class DateRangeViewTransformer implements DataTransformerInterface
{
    protected $options = array();

    public function __construct(OptionsResolverInterface $resolver, array $options = array())
    {
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'include_end' => true,
        ));

        $resolver->setAllowedValues(array(
            'include_end' => array(true, false),
        ));
    }

    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        if (!$value instanceof DateRange) {
            throw new UnexpectedTypeException($value, 'Jariff\MemberBundle\Model\DateRange');
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        if (!$value instanceof DateRange) {
            throw new UnexpectedTypeException($value, 'Jariff\MemberBundle\Model\DateRange');
        }

        if ($this->options['include_end']) {
            $value->end->setTime(23, 59, 59);
        }

        return $value;
    }
}