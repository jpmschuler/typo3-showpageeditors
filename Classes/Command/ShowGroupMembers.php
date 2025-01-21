<?php

namespace JPMSchuler\ShowPageEditors\Command;

use JPMSchuler\ShowPageEditors\Utility\GroupMemberHelper;
use JPMSchuler\ShowPageEditors\Utility\MarkupHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ShowGroupMembers extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $groupIds = explode(',', trim((string)$input->getArgument('gid')));
        $lineFeed = false;
        foreach ($groupIds as $gid) {
            if ($lineFeed) {
                $output->writeln('');
            }
            $data = GroupMemberHelper::showList($gid);
            $output->writeln(MarkupHelper::convertLinesForOutput($data));
            $lineFeed = true;
        }
        return 0;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Show which users are part of a group')
            ->setHelp('This command retrieves be_users which are in a group')
            ->addArgument('gid', InputArgument::REQUIRED, 'the group uid');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (!$input->getArgument('gid')) {
            $output->writeln('Please provide a gid');
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('gid: ');
            $pid = $helper->ask($input, $output, $question);
            $input->setArgument('gid', $pid);
        }
    }
}
