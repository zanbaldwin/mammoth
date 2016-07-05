<?php
declare(strict_types=1);

namespace AppBundle\Command;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends Command
{
    const NAME = 'fos:oauth-server:client:create';
    protected static $defaultGrantTypes = [
        'authorization_code',
        'token',
        'refresh_token'
    ];
    private $clientManager;

    public function __construct(ClientManagerInterface $clientManager)
    {
        parent::__construct(static::NAME);
        $this->clientManager = $clientManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new client')
            ->addArgument('name', InputArgument::REQUIRED, 'Sets the client name', null)
            ->addOption(
                'redirect-uri',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.',
                null
            )
            ->addOption(
                'grant-type',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..',
                null
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command creates a new client (OAuth2 Consumer).

  <info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

EOT
            );
    }

    protected function determineGrantTypes(array $specifiedGrantTypes) : array
    {
        return array_values(array_unique(array_merge(static::$defaultGrantTypes, $specifiedGrantTypes)));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->clientManager->createClient();
        $client->setName($input->getArgument('name'));
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($this->determineGrantTypes($input->getOption('grant-type')));
        $this->clientManager->updateClient($client);
        $output->writeln(sprintf(
            'Added a new client with name <info>%s</info> and public id <info>%s</info>.',
            $client->getName(),
            $client->getPublicId()
        ));
    }
}
