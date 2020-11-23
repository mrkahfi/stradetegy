<?php 

namespace Jariff\ProjectBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/

class DirtyWord extends Constraint
{
    public $message = 'Mengandung kata kotor "%dirty%"';
    
    /**
     * kalau target nya property saja,
     * kita tidak bisa mengetahui class dari property tersebut
     * kalau di tutorial yg lain maka nama class / entitinya diseting
     * di options,
     * kalau gitu, kita buat targetnya per class saja
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
    
    /*
     * nama service yg akan memvalidasi constraint ini
     */
    public function validatedBy()
    {
        return 'jariff_upulsa_validator_dirty_word';
    }
}
