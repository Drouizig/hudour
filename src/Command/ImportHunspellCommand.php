<?php

namespace App\Command;

use App\Entity\Affix;
use App\Entity\Enum\FlagType;
use App\Entity\Flag;
use App\Entity\Word;
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
    name: 'app:import-hunspell',
    description: 'Add a short description for your command',
)]
class ImportHunspellCommand extends Command
{
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('dic', InputArgument::OPTIONAL, '.dic file')
            ->addArgument('aff', InputArgument::OPTIONAL, '.aff file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = 10;
        $batchCounter = 0;

        $io = new SymfonyStyle($input, $output);
        $dicFile = $input->getArgument('dic');
        $affFile = $input->getArgument('aff');

        $affContent = file_get_contents($affFile);
        $affLines = explode("\n", $affContent);
        $remaningLines = 0;
        $currentFlag = null;
        $output->writeln('Importing affix file');
        foreach($affLines as $affLine) {
            if(str_starts_with($affLine, 'PFX')
            || str_starts_with($affLine, 'SFX')) {
                if ($remaningLines == 0) {
                    $flagData = explode(' ', $affLine);

                    $flag = $this->doctrine->getRepository(Flag::class)
                        ->findOneByName($flagData[1]);
                    if($flag === null ){
                        $flag = new Flag();
                        $flag->setName($flagData[1]);
                    }
                    if(str_starts_with($affLine, 'PFX')) {
                        $flag->setType(FlagType::Prefix);
                    } elseif(str_starts_with($affLine, 'SFX')) {
                        $flag->setType(FlagType::Suffix);
                    }
                    $currentFlag = $flag;
                    $remaningLines = $flagData[3];
                    $this->doctrine->getManager()->persist($flag);
                } else {
                    $prefixData = explode(' ', $affLine);
                    $remaningLines--;
                    $affix = new Affix();
                    $affix->setFlag($currentFlag);
                    $affix->setStripping($prefixData[2]);
                    $affix->setPrefix($prefixData[3]);
                    $affix->setCondition($prefixData[4]);
                    if(count($prefixData) > 5) {
                        $morphData = [];
                        for($i = 5; $i < count($prefixData); $i++) {
                            $morphData[] = $prefixData[$i];
                        }
                        $affix->setMorphologicalFields(join(' ', $morphData));
                    }

                    $this->doctrine->getManager()->persist($affix);
                }
                $batchCounter++;
                if($batchCounter >= $batchSize) {
                    $batchCounter = 0;
                    $this->doctrine->getManager()->flush();
                }
            }
        }

        $output->writeln('Affix file imported');
        $this->doctrine->getManager()->flush();
        $batchCounter = 0;

        $dicContent = file_get_contents($dicFile);
        $dicLines = explode("\n", $dicContent);
        //first line is number of entries
        unset($dicLines[0]);
        $flags = $this->doctrine->getRepository(Flag::class)->findBy([], ['name' => 'ASC']);

        $output->writeln('Importing dic file');

        $progressBar = new ProgressBar($output, count($dicLines));
        $progressBar->start();
        foreach($dicLines as $dicLine) {
            if($dicLine !== '') {

                $wordData = explode('/', $dicLine);
                $word = new Word();
                $word->setWord($wordData[0]);
                $this->doctrine->getManager()->persist($word);

                if(array_key_exists(1, $wordData)) {
                    //If there are flags
                    $wordMetadata = explode(' ', $wordData[1]);
                    $flagList = $wordMetadata[0];
                    while($flagList != '') {
                        $firstFlag = $this->getFirstFlag($flags, $flagList);
                        $word->addFlag($firstFlag);
                        $flagList = substr($flagList, strlen($firstFlag->getName()));
                    }

                    //If there are thing after the flags
                    if(strlen($wordData[1]) > strlen($wordMetadata[0])) {
                        $morphAndComments = substr($wordData[1], strlen($wordMetadata[0]));
                        $morphAndCommentsData = explode("#", $morphAndComments);
                        $word->setMorphologicalFields(trim($morphAndCommentsData[0]));
                        if(array_key_exists(1, $morphAndCommentsData)) {
                            $word->setComment(trim($morphAndCommentsData[1]));
                        }
                    }
                }

                $this->doctrine->getManager()->persist($word);

                $batchCounter++;
                if($batchCounter >= $batchSize) {
                    $batchCounter = 0;
                    $this->doctrine->getManager()->flush();
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $output->writeln('Dic file imported');

        $this->doctrine->getManager()->flush();

        return Command::SUCCESS;
    }

    public function getFirstFlag($flags, $flagList)
    {
        foreach($flags as $flag) {
            if(str_starts_with($flagList, $flag->getName())) {
                return $flag;
            }
        }
    }
}
