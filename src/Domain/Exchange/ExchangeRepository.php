<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange;

use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\CreateExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\DeleteExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\IndexExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\ReadExchangeRequest;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request\UpdateExchangeRequest;

final class ExchangeRepository
{
    private ExchangeGateway $gateway;

    public function __construct(ExchangeGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Exchange>
     */
    public function findBy(IndexExchangeRequest $request): array
    {
        return \array_map(function (array $exchange) {
            return (new ExchangeFactory())->make($exchange);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateExchangeRequest $request): Exchange
    {
        $exchange = (new ExchangeFactory())->make($request);

        $exchange->setId($this->gateway->push($exchange));

        return $exchange;
    }

    public function getById(ReadExchangeRequest $request): Exchange
    {
        $factory = new ExchangeFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateExchangeRequest $request): Exchange
    {
        $exchange = (new ExchangeFactory())->make($request);

        $this->gateway->shift($exchange);

        return $exchange;
    }

    public function remove(DeleteExchangeRequest $request): Exchange
    {
        $factory = new ExchangeFactory();
        $data = $this->gateway->get($factory->make($request));

        $exchange = $factory->make($data);

        $this->gateway->pop($exchange);

        return $exchange;
    }
}
