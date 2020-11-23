<?php 

namespace Jariff\MemberBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/

class Pending extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
    /*
     * nama service yg akan memvalidasi constraint ini
     */
    public function validatedBy()
    {
        return 'jariff_pending_validator';
    }
}
