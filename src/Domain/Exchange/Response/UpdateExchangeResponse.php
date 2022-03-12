<?php declare(strict_types=1);

namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Response;

use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Exchange;
use FlexPHP\Messages\ResponseInterface;

final class UpdateExchangeResponse implements ResponseInterface
{
    public $exchange;

    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }
}
