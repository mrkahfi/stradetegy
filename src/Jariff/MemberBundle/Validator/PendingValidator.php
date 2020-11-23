<?php 

namespace Jariff\MemberBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Jariff\MemberBundle\Entity\Member;

class PendingValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function validate($entity, Constraint $constraint)
    {
        // validate unique email address
        $pending = $this->em->getRepository('JariffMemberBundle:Pending')->findOneByEmail($entity->getEmail());
        $member = $this->em->getRepository('JariffMemberBundle:Member')->findOneByEmail($entity->getEmail());
        if($pending && $member)
            if ($member->getStatus() != Member::EXPIRED && $member->getStatus() != Member::CANCELLED 
               && $member->getStatus() != Member::SUSPENDED) {
                $this->context->addViolation('This email has already been used. Please contact us to reactivate your account.');
        }
        // validate PIF
        if ( in_array($entity->getPayment(), array('paypal', 'check', 'bankwire')) and $entity->getPaymentTerm() == 'mtm') {
            $this->context->addViolation('Paypal, Check, and Bankwire payment only available for Paid in Full (PIF) payment term.');
        }

        // validate CC
        if ( $entity->getPayment() == 'cc'
            and (
                is_null($entity->getSecureCode()) or
                is_null($entity->getNumber()) or
                is_null($entity->getExpired())
                )
            ) {
            $this->context->addViolation('Please complete your credit card credential.');
    }

    if ( $entity->getPayment() == 'cc' ){
            // validate CC type
        if ( 
            $entity->getCcType() == 'american express' and 
            ( strlen($entity->getNumber()) != 16 or substr($entity->getNumber(), 0, 1) != '3' )
            ) {
            $this->context->addViolation('Your AmEx credit card number is invalid.');
    }  
    if ( 
        $entity->getCcType() == 'visa' and 
        ( strlen($entity->getNumber()) != 19 or substr($entity->getNumber(), 0, 1) != '4' )
        ) {
        $this->context->addViolation("Your Visa credit card number is invalid.");
}  
if ( 
    $entity->getCcType() == 'mastercards' and 
    ( strlen($entity->getNumber()) != 19 or substr($entity->getNumber(), 0, 1) != '5' )
    ) {
    $this->context->addViolation('Your Mastercards credit card number is invalid.');
}  
if ( 
    $entity->getCcType() == 'discover' and 
    ( strlen($entity->getNumber()) != 19 or substr($entity->getNumber(), 0, 1) != '6' )
    ) {
    $this->context->addViolation('Your Discover credit card number is invalid.');
}
}

        // validate plan
if ( ! ($entity->getEverythingPlan() or $entity->getCustomPlan())) {
    $this->context->addViolation('Please choose either EVERYTHING plan or BUILD A plan.');
}
}
}
