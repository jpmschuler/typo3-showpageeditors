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
    protected const FIRST_WORD_PAD = 12;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (PageVisibleHelper::showList($input->getArgument('pid')) as $line) {
            switch (substr($line, 0, 3)) {
                case 'PID':
                    $output->writeln('<info>' . static::padLine($line) . '</info>');
                    break;
                case 'GID':
                    $output->writeln('<comment>' . static::padLine($line, ' ') . '</comment>');
                    break;
                case 'UID':
                    $output->writeln('<comment>' . static::padLine($line, ' \\') . '</comment>');
                    break;
                default:
                    $output->writeln($line);
            }
        }
        return 0;
    }

    protected static function padLine($line, $prefix = ''): string
    {
        $firstWord = explode(' ', trim($line))[0];
        $rest = trim(strstr($line, ' '));
        return str_pad($prefix . $firstWord, self::FIRST_WORD_PAD, ' ') . $rest;
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
     * @param InputInterface $input
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
