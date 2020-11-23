<?php


namespace Jariff\AdminBundle\Command\Cron;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jariff\AdminBundle\Entity\Inbound;

/**
 * ChangePasswordCommand
 */
class EsignMemberCheckCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-esign-member-check');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em   = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('database_connection'); //alias for doctrine.dbal.default_connection

        $today = new \DateTime();
        $today->format('Y-m-d');


        $em->flush();
        
    }
}
