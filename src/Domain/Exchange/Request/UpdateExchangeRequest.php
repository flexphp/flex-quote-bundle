<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateExchangeRequest implements RequestInterface
{
    public $id;

    public $date;

    public $currency;

    public $quote;

    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->date = $data['date'] ?? null;
        $this->currency = $data['currency'] ?? null;
        $this->quote = $data['quote'] ?? null;
    }
}
