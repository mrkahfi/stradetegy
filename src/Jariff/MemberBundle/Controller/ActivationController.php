<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\MemberBundle\Controller;

use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\MemberCC;
use Jariff\MemberBundle\Entity\MemberSubscription;
use Jariff\MemberBundle\Entity\MemberProfile;
use Jariff\MemberBundle\Entity\MemberARB;
// use Jariff\MemberBundle\Entity\MemberAlert;
// use Jariff\MemberBundle\Entity\MemberSearch;
// use Jariff\MemberBundle\Entity\Payment;
// use Jariff\AdminBundle\Entity\Leads;
// use Jariff\MemberBundle\Entity\MemberDownload;
// use Jariff\MemberBundle\Entity\Pending;

use Jariff\ProjectBundle\Util\Util;
/**
 * controller ini didefinisikan sebagai service,
 * karena untuk cron, kelihatannya tidak diperlukan lib yg berkaitan dengan http
 * lihat definisi sebagai service di Resources/config/service.yml
 */
class ActivationController
{
    /**
     * karena controller didefinisikan sebagai service,
     * maka disini kita tidak bisa extend FrameworkBundle/Controller yg biasa dipakai,
     * oleh karena itu service2 yg dibutuhkan harus di definisikan ulang,
     * @$EntityManager
     */
    private $em;
    private $templating;
    private $mailer;
    private $encoder;
    private $sc;

    public function setEm($em){
        $this->em = $em;
    }
    public function setTemplating($templating){
        $this->templating = $templating;
    }
    public function setMailer($mailer){
        $this->mailer = $mailer;
    }
    public function setEncoder($encoder){
        $this->encoder = $encoder;
    }
    public function setSc($sc){
        $this->sc = $sc;
    }

    /**
     * fungsi ini dijalankan dari GenerateTreeCommand
     * dengan parameter member_id dari tabel activation_queue
     * tabel activation_queue ini berisi data member pending yg sudah siak
     * untuk diaktifkan,
     * tabel ini digenerate dari UniqueConfirmCommand
     */
    public function activate($member_pending_id, $unclaimed_payment_id)
    {
        $pending = $this->em->getRepository('JariffMemberBundle:Pending')->find($member_pending_id);
        if(!$pending){
            throw new \Exception('Unable to find requested data.');
        }

        // calculate monthly price
        $payment = $this->em->getRepository('JariffMemberBundle:Payment')->find($unclaimed_payment_id);

        if($payment->getAmount() < $pending->getTotalPrice()){
            throw new \Exception('Insufficent amount of money');
        }

        $salt = md5(uniqid());
        $hashed = $this->encoder->encodePassword($pending->getPassword(), $salt);
        $member = new Member();
        $member->setRoles(      array('ROLE_MEMBER'));
        $member->setPassword(   $hashed);
        $member->setSalt(       $salt);
        $member->setEmail(      $pending->getEmail());
        if ($pending->getPaymentTerm() == 'mtm') {
            $member->setExpiredDate(null);
        } else 
        if ($pending->getPaymentTerm() == 'pif') {
            $dateExpired = new \DateTime();
            $dateExpired->modify('+'.$pending->getMonthValue().'1 month');
            $member->setExpiredDate($dateExpired);
        }
        $this->em->persist($member);

        if ($pending->getPayment() == 'cc') {
            $cc = new MemberCC();
            $cc->setNumber($pending->getNumber());        
            $cc->setExpired($pending->getExpired());        
            $cc->setSecureCode($pending->getSecureCode());
            $cc->setMember($member);
            $this->em->persist($cc);

            if ($pending->getPaymentTerm() == 'mtm') {
                $chargeDate = new \DateTime();
                // ARB akan jalan bulan berikutnya,
                // pembayaran pertama kali digenerate dari validasi admin
                $chargeDate->modify('+1 month');
                while($chargeDate < $pending->getExpired()){
                    $arb = new MemberARB();
                    $arb->setAmount($pending->getTotalPrice());
                    $arb->setChargeDate(new \DateTime($chargeDate->format('Y-m-d H:i:s')));
                    $arb->setMember($member);

                    $this->em->persist($arb);

                    $chargeDate->add(new \DateInterval('P1M'));
                }
            }
        }

        $plan = new MemberSubscription();
        $plan->setHistory(          $pending->getHistory());        
        $plan->setHistoryValue(         $pending->getHistoryValue());        
        $plan->setBigPicture(         $pending->getBigPicture());        
        $plan->setBigPictureValue(    $pending->getBigPictureValue());        
        $plan->setSearch(         $pending->getSearch());        
        $plan->setSearchValue(        $pending->getSearchValue());        
        $plan->setDownload(           $pending->getDownload());        
        $plan->setDownloadValue(      $pending->getDownloadValue());        
        $plan->setMember($member);
        $this->em->persist($plan);

        $profile = new MemberProfile();
        $profile->setCountry($pending->getCountry());        
        $profile->setPhone($pending->getPhone());        
        $profile->setDateRegistration($pending->getDateRegistration());        
        $profile->setDateActivation(new \DateTime());        
        $profile->setFirstName($pending->getFirstName());        
        $profile->setLastName($pending->getLastName());        
        $profile->setCompanyName($pending->getCompanyName());        
        $profile->setSalutation($pending->getSalutation());
        $profile->setMember($member);
        $this->em->persist($profile);    

        $payment->setMember($member);

        $this->em->remove($pending);
        $this->em->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('Congratulation, your Stradetegy membership just activated right now')
            ->setFrom('no-reply@stradetegy.com')
            ->setTo($member->getEmail())
            ->setBcc('ardianys@outlook.com')
            ->setBody($this->templating->render('JariffAdminBundle:Email:activated.txt.twig', array('origin' => $origin)))
            ->addPart($this->templating->render('JariffAdminBundle:Email:activated.html.twig', array('origin' => $origin), 'text/html'))
        ;
        $this->mailer->send($message);

        return true;
    }
}