<?php

namespace FileChecker\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'sum-values:from-files',
    description: 'Команда ищет в директории файлы и подсчитывает сумму значений из них',
)]
final class SumCommand extends Command
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->addArgument('path', InputArgument::REQUIRED, 'Путь до директории откуда начать поиск')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Имя файла который ищем', 'count');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $filename = $input->getArgument('filename');

        $finder = (new Finder())->in($path);

        $result = $this->calculateSumFromFile($filename, $finder);

        $output->writeln("Итоговая сумма значений из файлов {$filename} = {$result}");

        return Command::SUCCESS;
    }

    private function calculateSumFromFile(string $filename, Finder $finder): int
    {
        $sum = 0;

        $files = $this->searchFiles($filename, $finder);

        foreach ($files as $file) {
            $sum += (int)$file->getContents();
        }

        $directories = $finder->directories();
        foreach ($directories as $directory) {
            $sum += $this->calculateSumFromFile($filename, $directory);
        }

        return $sum;
    }

    private function searchFiles(string $filename, Finder $finder): Finder
    {
        return $finder
            ->files()
            ->filter(fn(\SplFileInfo $file) => $file->getFilename() === $filename);
    }
}