<?php


namespace Jariff\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jariff\MemberBundle\Entity\Member;
use Jariff\MemberBundle\Entity\Permission;

/**
 * ChangePasswordCommand
 */
class ExpireCheckupCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-expire-checkup')
            ->setDefinition(array(
                new InputArgument('day', InputArgument::REQUIRED, 'Days before expired'),
            ));
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em         = $this->getContainer()->get('doctrine.orm.entity_manager');
        $templating = $this->getContainer()->get('templating');
        $mailer     = $this->getContainer()->get('mailer');
        $day        = $input->getArgument('day');
        
        $expiring = $em->getRepository('JariffMemberBundle:Member')->findExpiring($day);

        if ($expiring) {
            foreach ($expiring as $entity) {
                $output->writeln($entity->getEmail().' expiring in '.$day.' days');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Attention please, Your Stradetegy membership will expired in '.$day.' days')
                    ->setFrom('no-reply@stradetegy.com')
                    ->setTo($entity->getEmail())
                    ->setCc('sales@stradetegy.com')
                    ->setBcc('ardianys@outlook.com')
                    ->setBody($templating->render('JariffAdminBundle:Email:expiring.txt.twig', array('entity' => $entity)))
                    ->addPart($templating->render('JariffAdminBundle:Email:expiring.html.twig', array('entity' => $entity), 'text/html'))
                ;
                $mailer->send($message);
                $output->writeln('Sending mail ...');
                sleep(1);
            }
        }
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('day')) {
            $day = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please input how long until active member expired in days :',
                function($day) {
                    if (empty($day)) {
                        throw new \Exception('Day can not be empty');
                    }
                    return $day;
                }
            );
            $input->setArgument('day', $day);
        }
    }
}
