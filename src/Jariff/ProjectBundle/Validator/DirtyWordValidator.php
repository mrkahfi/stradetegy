<?php 

namespace Jariff\ProjectBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DirtyWordValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function validate($value, Constraint $constraint)
    {
        $setting = $this->em->getRepository('JariffProjectBundle:Setting')->findOneByName('dirty');
        $texts = explode(' ', $setting->getValue());
        foreach ($texts as $text) {
            if(stristr($value, $text) != ''){
                $this->context->addViolation($constraint->message, array('%dirty%' => $text));
            }
        }
    }
}
