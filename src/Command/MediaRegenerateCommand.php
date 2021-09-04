<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Command;

use Exception;
use NumberNine\Media\ThumbnailGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MediaRegenerateCommand extends Command implements ImageSizeAwareCommandInterface
{
    protected static $defaultName = 'numbernine:media:regenerate';

    public function __construct(private ThumbnailGenerator $thumbnailGenerator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Regenerates all images variations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->thumbnailGenerator->setIo($io);
            $this->thumbnailGenerator->regenerateImageVariations();
        } catch (Exception $e) {
            $io->error('Unable to regenerate images variations. ' . $e->getMessage());
            $io->text($e->getTraceAsString());
            return 1;
        }

        $io->success('Images variations successfully regenerated.');

        return 0;
    }
}
