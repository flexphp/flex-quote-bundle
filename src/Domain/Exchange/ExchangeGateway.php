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

interface ExchangeGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(Exchange $exchange): int;

    public function get(Exchange $exchange): array;

    public function shift(Exchange $exchange): void;

    public function pop(Exchange $exchange): void;
}
