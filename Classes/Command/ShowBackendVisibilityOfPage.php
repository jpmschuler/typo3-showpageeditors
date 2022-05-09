<?php

namespace JPMSchuler\ShowPageEditors\Command;

use JPMSchuler\ShowPageEditors\Utility\PageVisibleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ShowBackendVisibilityOfPage extends Command
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(PageVisibleHelper::showList($input->getArgument('pid')));

        return 0;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Show for which users a page is mounted')
            ->setHelp('This command retrieves be_groups and be_users which have access to a page')
            ->addArgument('pid', InputArgument::REQUIRED, 'the table name');
    }

    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @see InputInterface::bind()
     * @see InputInterface::validate()
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (!$input->getArgument('pid')) {
            $output->writeln('Please provide a pid');
            $helper = $this->getHelper('question');
            $question = new Question('pid: ');
            $pid = $helper->ask($input, $output, $question);
            $input->setArgument('pid', $pid);
        }
    }
}
