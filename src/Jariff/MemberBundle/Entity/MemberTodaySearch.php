<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jariff
 * Date: 3/8/14
 * Time: 12:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Jariff\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jariff\ProjectBundle\Util\Util;

/**
 * Jariff\MemberBundle\Entity\MemberTodaySearch
 *
 * @ORM\Entity
 * @ORM\Table(name="member_today_search")
 * @ORM\HasLifecycleCallbacks
 */
class MemberTodaySearch {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $date_search;

    /**
     * @ORM\Column(type="integer", length=10)
     * @Assert\NotBlank()
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="member")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $member;



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
     * Set date_search
     *
     * @param \DateTime $dateSearch
     * @return MemberTodaySearch
     */
    public function setDateSearch($dateSearch)
    {
        $this->date_search = $dateSearch;
    
        return $this;
    }

    /**
     * Get date_search
     *
     * @return \DateTime 
     */
    public function getDateSearch()
    {
        return $this->date_search;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return MemberTodaySearch
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set member
     *
     * @param \Jariff\MemberBundle\Entity\Member $member
     * @return MemberTodaySearch
     */
    public function setMember(\Jariff\MemberBundle\Entity\Member $member = null)
    {
        $this->member = $member;
    
        return $this;
    }

    /**
     * Get member
     *
     * @return \Jariff\MemberBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
}