<?php

/*
 * Infaqhub Indonesia (c)
 */

namespace Jariff\ProjectBundle\Security;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

class PasswordEncoder extends BasePasswordEncoder
{
    public function encodePassword($raw, $salt)
    {
        // echo base64_encode(hash('sha256', 'asdfasdf{434c26663d47dcf083aa47b6cb5889c7}'));
        return hash('sha256', $raw.'{'.$salt.'}');
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }
}
