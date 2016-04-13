<?php

namespace Lexik\Bundle\MonologBrowserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @author Julien Martin <julien.martin@jum4.org>
 */
class TruncateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('lexik:monolog-browser:truncate')
            ->setDescription('Truncate Monolog entries')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Remove all monolog entries ? (Y|n)', false);
        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $tableName = $this->getContainer()->getParameter('lexik_monolog_browser.doctrine.table_name');
        $conn = $this->getContainer()->get('lexik_monolog_browser.doctrine_dbal.connection');

        $error = false;
        try {
            $query = $conn->getDatabasePlatform()->getTruncateTableSQL($tableName);

            $conn->executeUpdate($query);

            $output->writeln(sprintf('<info>Truncated table <comment>%s</comment> for Doctrine Monolog connection</info>', $tableName));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not truncate table <comment>%s</comment> for Doctrine Monolog connection</error>', $tableName));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $error = true;
        }

        return $error ? 1 : 0;
    }
}
