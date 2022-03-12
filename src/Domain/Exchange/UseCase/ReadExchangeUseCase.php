<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange\UseCase;

use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\ExchangeRepository;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\ReadExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Response\ReadExchangeResponse;

final class ReadExchangeUseCase
{
    private ExchangeRepository $exchangeRepository;

    public function __construct(ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    public function execute(ReadExchangeRequest $request): ReadExchangeResponse
    {
        $exchange = $this->exchangeRepository->getById($request);

        return new ReadExchangeResponse($exchange);
    }
}
