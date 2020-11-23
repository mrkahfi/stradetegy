<?php

namespace Jariff\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\Payment;
use AuthorizeNet;

class CCPayCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-cc-pay');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em   = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('database_connection'); //alias for doctrine.dbal.default_connection

        // get unpaid invoice
        $invoices = $conn->fetchAll('
            SELECT i.id as i_id
            FROM invoice i
            LEFT JOIN member_cc mcc ON i.member_id = mcc.member_id
            where 
            mcc.status = "active"
            and i.payment_id is null
        ');

        // var_dump($invoices);die();

        if ($invoices) {
            foreach ($invoices as $id) {
                $invoice = $em->getRepository('JariffMemberBundle:Invoice')->find($id['i_id']);
                $member = $invoice->getMember();
                $profile = $member->getProfile();

                $payment = new Payment();
                $payment->setAmount($invoice->getAmount());
                $payment->setType('cc');
                $payment->setNote('Auto executed CC payment');
                $payment->setAdmin($invoice->getSales());
                $payment->setMember($member);
                $payment->setInvoice($invoice);

                $output->writeln('New cc payment');


                $params = $this->getContainer()->parameters['jariff_authorize_net'];
                define("AUTHORIZENET_API_LOGIN_ID", $params['api_login_id']);
                define("AUTHORIZENET_TRANSACTION_KEY", $params['api_transaction_key']);

                $sale           = new AuthorizeNet\API\AIM;
                $sale->amount   = $invoice->getAmount();
                $sale->card_num = $member->getCC()->getNumber();
                $sale->exp_date = $member->getCC()->getExpired()->format('m/y');
                $response       = $sale->authorizeAndCapture();
                var_dump($response);
                $out            = new AuthorizeNet\API\AIMResponse($response->response, ",", "|", array());

                if ($out->approved) {
                    echo 'approved';
                    $em->persist($payment);
                } else {

                    if ($out->declined) {
                        $member->getCC()->setStatus('declined');
                        echo 'declined';
                    } elseif ($out->error) {
                        $member->getCC()->setStatus('error');
                        echo 'error';
                    } elseif ($out->held) {
                        $member->getCC()->setStatus('held');
                        echo 'held';
                    } 

                    // send notice to system
                    $inbound = new Inbound();
                    $inbound->setEmail($member->getEmail());
                    $inbound->setPhone($member->getProfile()->getPhone());
                    $inbound->setBusiness($member->getProfile()->getCompanyName());
                    $inbound->setName($member->getProfile()->getFirstName().' '.$member->getProfile()->getLastName());
                    $inbound->setCountry($member->getProfile()->getCountry());
                    $inbound->setIpAddress(null);
                    $inbound->setVisitedPage(null);
                    $inbound->setSource('initial_decline');
                    $inbound->setDescription('Credit card status for member #'.$member->getNumber().' is '.ucfirst($member->getCC()->getStatus()));
                    $inbound->setMember($member);

                    $em->persist($inbound);
                }
                $em->flush();
            }
        } else {
            $output->writeln('No unpaid invoice');
            // send notice
        }
    }
}
