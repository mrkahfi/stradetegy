<?php 

namespace Jariff\ProjectBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RouterCheckValidator extends ConstraintValidator
{
    protected $container;

    public function __construct($container){
        $this->container = $container;
    }
    
    public function validate($value, Constraint $constraint)
    {
        if($value != '#'){
            $router = $this->container->get('router')->getRouteCollection()->get($value);

            if(!$router)
                $this->context->addViolation($constraint->message, array('%router%' => $value));
        }



    }
}
