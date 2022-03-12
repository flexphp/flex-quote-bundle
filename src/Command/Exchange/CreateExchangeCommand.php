<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Command\Exchange;

use DateTime;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\ExchangeRepository;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\CreateExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\IndexExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\CreateExchangeUseCase;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase\IndexExchangeUseCase;
use Psr\Log\LoggerInterface;
use Swap\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateExchangeCommand extends Command
{
    private LoggerInterface $logger;

    private ExchangeRepository $exchangeRepository;

    public function __construct(LoggerInterface $logger, ExchangeRepository $exchangeRepository)
    {
        $this->logger = $logger;
        $this->exchangeRepository = $exchangeRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('quote:exchanges:create')
            ->setDescription('Command to Create on Exchange')
            ->addArgument('Date', InputArgument::OPTIONAL)
            ->addArgument('Currency', InputArgument::OPTIONAL)
            ->addArgument('Quote', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $input->getArguments();
        $date = $data['Date'] ?? \date('Y-m-d');
        $fromCurrency = 'USD';
        $toCurrency = $data['Currency'] ?? 'COP';
        $quote = 3000.00;

        $request = new IndexExchangeRequest([
            'date' => $date,
            'currency' => $toCurrency,
        ], 1, 1);
        $useCase = new IndexExchangeUseCase($this->exchangeRepository);
        $response = $useCase->execute($request);

        if (!empty($response->exchanges)) {
            $output->writeln('Already exists Exchange ' . $response->exchanges[0]->currency() . ' for: ' . $date);

            return Command::SUCCESS;
        }

        if ($_ENV['APP_ENV'] !== 'prod') {
            $output->writeln('Run mock in database ' . $toCurrency . ' for: ' . $date);
        } else {
            $output->writeln('Query API in progress ' . $toCurrency . ' for: ' . $date);

            $rate = $this->getSwap()->historical($fromCurrency . '/' . $toCurrency, new DateTime($date));

            $quote = $rate->getValue();
        }

        $request = new CreateExchangeRequest([
            'date' => $date,
            'currency' => $toCurrency,
            'quote' => (string)$quote,
        ]);
        $useCase = new CreateExchangeUseCase($this->exchangeRepository);
        $response = $useCase->execute($request);

        $output->writeln('Create Exchange ' . $response->exchange->id() . ' to: ' . $toCurrency . ' for: ' . $date);

        return Command::SUCCESS;
    }

    private function getSwap()
    {
        // @see https://github.com/florianv/swap
        return (new Builder())
            // ->add('exchange_rates_api', ['access_key' => $_ENV['EXCHANGE_API_KEY']])
            ->add('currency_converter', ['access_key' => $_ENV['EXCHANGE_API_KEY'], 'enterprise' => false])
            ->build();
    }
}
