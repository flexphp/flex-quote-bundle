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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class ExchangeFactory
{
    use FactoryExtendedTrait;

    public function make($data): Exchange
    {
        $exchange = new Exchange();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $exchange->setId((string)$data['id']);
        }

        if (isset($data['date'])) {
            $exchange->setDate(\is_string($data['date']) ? new \DateTime($data['date']) : $data['date']);
        }

        if (isset($data['currency'])) {
            $exchange->setCurrency((string)$data['currency']);
        }

        if (isset($data['quote'])) {
            $exchange->setQuote((string)$data['quote']);
        }

        return $exchange;
    }
}
