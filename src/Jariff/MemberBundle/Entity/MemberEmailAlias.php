<?php

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jariff\MemberBundle\Entity\MemberEmailAlias
 *
 * @ORM\Table(name="member_email_alias")
 * @ORM\Entity(repositoryClass="Jariff\MemberBundle\Repository\MemberEmailAliasRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberEmailAlias
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="MemberEmail", inversedBy="alias")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $memberEmail;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return MemberEmailAlias
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return MemberEmailAlias
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set memberEmail
     *
     * @param \Jariff\MemberBundle\Entity\MemberEmail $memberEmail
     * @return MemberEmailAlias
     */
    public function setMemberEmail(\Jariff\MemberBundle\Entity\MemberEmail $memberEmail = null)
    {
        $this->memberEmail = $memberEmail;
    
        return $this;
    }

    /**
     * Get memberEmail
     *
     * @return \Jariff\MemberBundle\Entity\MemberEmail 
     */
    public function getMemberEmail()
    {
        return $this->memberEmail;
    }
}