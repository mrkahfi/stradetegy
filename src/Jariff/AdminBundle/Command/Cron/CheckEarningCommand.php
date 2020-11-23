<?php


namespace Jariff\AdminBundle\Command\Cron;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jariff\AdminBundle\Entity\Earning;

/**
 * ChangePasswordCommand
 */
class CheckEarningCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-admin-earning-check');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em   = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('database_connection'); //alias for doctrine.dbal.default_connection

        $date = new DateTime();
        $today = $date->format('d');
        $thisMonth = $date->format('m');

        $admins = $conn->fetchAll("SELECT admin AS admin_id, joinDate AS join_date FROM Earning 
            WHERE joinDate BETWEEN '2012-12-".$today." 00:00:00' AND '2012-12-".$today." 23:59:59'");

        if ($admins) {
            foreach ($admins as $adminId) {
                $admin = $em->getRepository('JariffAdminBundle:Admin')->find($adminId['admin_id']);
                $oldEarnings = $em->getRepository('JariffAdminBundle:Earning')->findBy(array('array'=>$admin, 'id'=>'DESC'), 3, null);

                $earning = new Earning();

                $joinDate = DateTime::createFromFormat('Y-m-d hh:mm:ss', $adminId['join_date']);
                $month = $joinDate->format('m');
                $monthDiff = $thisMonth - $month;
                $mod = $monthDiff % 3;

                $achievement = $admin->getSubscription()-$oldEarnings[0];
                $earning->setAchievement($achievment);
                $monthly = 3000;
                if ($achievement >= 80) {
                    $monthly = $monthly + ($mod * 0.15 * $monthly);
                }
                $earning->setMonthly($monthly);

                $monthDiff = $thisMonth - $month + 1;

                $achievement = $admin->getSubscription()-$oldEarnings;
                $earning->setAchievement($achievment);

                $quarterAchieved = true;
                foreach ($oldEarnings as $oldEarning) {
                    if ($oldEarning->getAchievement() < 80) $quarterAchieved = false;
                }
                $monthly = 3000;
                if ($achievement >= 80 && $quarterAchieved) {
                    $monthly = $monthly + 0.25 * $monthly;
                    $earning->setQuarterly($quarterly);
                }
                $earning->setEndOf($date);
                $earning->setPaid($monthly);
                $earning->setCorpEarning(20000-$monthly);
                $em->persist($earning);
            }
        }
    }
}
