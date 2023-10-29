<?php

namespace App\Command;

use App\Entity\Affix;
use App\Entity\Enum\FlagType;
use App\Entity\Flag;
use App\Entity\Word;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export-hunspell-dic',
    description: 'Add a short description for your command',
)]
class ExportHunspellDicCommand extends Command
{
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = 10;
        $batchCounter = 0;

        /** @var Collection<Word> $words */
        $words = $this->doctrine->getRepository(Word::class)->findBy([], ['word' => 'ASC']);

        $output->writeln(count($words));

        foreach($words as $word) {
            $string = $word->getWord();
            if($word->getFlags()->count() > 0) {
                $flagString = '';
                foreach($word->getFlags() as $flag) {
                    $flagString .= $flag->getName();
                }
                $string .= sprintf('/%s', $flagString);
                if($word->getMorphologicalFields()) {
                    $string .= sprintf(' %s', $word->getMorphologicalFields());
                }
                if($word->getComment()) {
                    $string .= sprintf(' #%s', $word->getComment());
                }
            }
            $output->writeln($string);
        }

        return Command::SUCCESS;
    }

}
