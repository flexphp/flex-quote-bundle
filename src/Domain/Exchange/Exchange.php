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

use DateTimeInterface;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\ToArrayTrait;

final class Exchange
{
    use ToArrayTrait;

    private $id;

    private $date;

    private $currency;

    private $quote;

    public function id(): ?int
    {
        return $this->id;
    }

    public function date(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function currency(): ?string
    {
        return $this->currency;
    }

    public function quote(): ?string
    {
        return $this->quote;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function setQuote(?string $quote): void
    {
        $this->quote = $quote;
    }
}
