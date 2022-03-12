<?php declare(strict_types=1);

namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexExchangeResponse implements ResponseInterface
{
    public $exchanges;

    public function __construct(array $exchanges)
    {
        $this->exchanges = $exchanges;
    }
}
