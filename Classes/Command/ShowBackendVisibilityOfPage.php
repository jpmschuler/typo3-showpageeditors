<?php

namespace JPMSchuler\ShowPageEditors\Command;

use JPMSchuler\ShowPageEditors\Utility\MarkupHelper;
use JPMSchuler\ShowPageEditors\Utility\PageVisibleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ShowBackendVisibilityOfPage extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = PageVisibleHelper::showList($input->getArgument('pid'));
        $output->writeln(MarkupHelper::convertLinesForOutput($data));

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
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (!$input->getArgument('pid')) {
            $output->writeln('Please provide a pid');
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('pid: ');
            $pid = $helper->ask($input, $output, $question);
            $input->setArgument('pid', $pid);
        }
    }
}
