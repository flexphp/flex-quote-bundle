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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\DateTimeTrait;
use FlexPHP\Messages\RequestInterface;

final class IndexExchangeRequest implements RequestInterface
{
    use DateTimeTrait;

    public $id;

    public $date;

    public $currency;

    public $quote;

    public $_page;

    public $_limit;

    public $_offset;

    public function __construct(array $data, int $_page, int $_limit = 50, ?string $timezone = null)
    {
        $this->id = $data['id'] ?? null;
        $this->date = $data['date'] ?? null;
        $this->currency = $data['currency'] ?? null;
        $this->quote = $data['quote'] ?? null;
        $this->_page = $_page;
        $this->_limit = $_limit;
        $this->_offset = $this->getOffset($this->getTimezone($timezone));
    }
}
