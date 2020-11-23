<?php


namespace Jariff\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jariff\AdminBundle\Entity\Admin;

/**
 * ChangePasswordCommand
 */
class IntegrityCheckupCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('jariff-integrity-checkup');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // lead activity status = [Complete|...]
        $this->compare('lead_stage', 'name', 'Closed-Lost', 3);
    }

    public function compare($table, $column, $value, $id)
    {
        $conn = $this->getContainer()->get('database_connection');
        $sql  = 'SELECT id FROM `'.$table.'` where '.$column.' = "'.$value.'"';
        $id2   = $conn->fetchColumn($sql);

        if (intval($id2) != $id) {
            $data = $table.', '.$column.', '.$value.', '.$id2.', '.$id;
            $output->writeln($data);
            mail('ardianys@gmail.com', 'Error Integrity Data', $data);
        }

    }
}
