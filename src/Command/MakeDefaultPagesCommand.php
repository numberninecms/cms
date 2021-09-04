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

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Model\General\Settings;
use NumberNine\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

use function Symfony\Component\String\u;

final class MakeDefaultPagesCommand extends Command
{
    protected static $defaultName = 'numbernine:make:default-pages';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ConfigurationReadWriter $configurationReadWriter,
        private Environment $twig,
        private SluggerInterface $slugger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates default pages')
            ->setHelp(<<<HELP

This command creates the following pages:

* <info>My account</info>
* <info>Privacy</info>

HELP
            )
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'Username of the administrator')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getOption('username');

        if ($username) {
            $user = $this->userRepository->findOneBy(['username' => $username]);

            if (!$user || !in_array('Administrator', $user->getRoles())) {
                $io->error(sprintf('Unknown administrator user "%s"', $username));
                return Command::FAILURE;
            }
        } else {
            $user = current($this->userRepository->findByRoleName('Administrator'));

            if (!$user) {
                $io->error('You must create an admin user');
                $io->writeln(
                    ' * <fg=blue>Run</> <comment>bin/console numbernine:user:create --admin</comment> ' .
                     'to create one.'
                );
                return Command::FAILURE;
            }
        }

        try {
            $this->createMyAccountPage($user);
            $this->createPrivacyPage($user);
        } catch (Exception $e) {
            $io->error('Unable to create default pages. ' . $e->getMessage());
            return 1;
        }

        $io->success('Default pages successfully created.');

        return 0;
    }

    private function createMyAccountPage(User $user): void
    {
        $page = (new Post())
            ->setCustomType('page')
            ->setTitle('My account')
            ->setContent('')
            ->setAuthor($user)
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new DateTime())
            ->setPublishedAt(new DateTime());

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->configurationReadWriter->write(Settings::PAGE_FOR_MY_ACCOUNT, $page->getId());
    }

    private function createPrivacyPage(User $user): void
    {
        $siteTitle = $this->configurationReadWriter->read(Settings::SITE_TITLE);
        $content = $this->twig->render('@NumberNine/templates/privacy.html.twig', [
            'company_name' => 'COMPANY NAME',
            'website_name' => $siteTitle ?? 'WEBSITE NAME',
            'website_url' => sprintf('https://%s.com', $this->slugger->slug(u($siteTitle)->folded())),
        ]);

        $page = (new Post())
            ->setCustomType('page')
            ->setTitle('Privacy policy')
            ->setContent($content)
            ->setAuthor($user)
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new DateTime())
            ->setPublishedAt(new DateTime());

        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->configurationReadWriter->write(Settings::PAGE_FOR_PRIVACY, $page->getId());
    }
}
