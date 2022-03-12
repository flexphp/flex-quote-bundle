<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\Exchange;
use FlexPHP\Bundle\QuoteBundle\Domain\Exchange\ExchangeGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLExchangeGateway implements ExchangeGateway
{
    private $conn;

    private $operator = [
        //
    ];

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'exchange.Id as id',
            'exchange.Date as date',
            'exchange.Currency as currency',
            'exchange.Quote as quote',
        ]);
        $query->from('`Exchanges`', '`exchange`');

        $query->orderBy('exchange.Date', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('exchange', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Exchange $exchange): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Exchanges`');

        $query->setValue('Date', ':date');
        $query->setValue('Currency', ':currency');
        $query->setValue('Quote', ':quote');

        $query->setParameter(':date', $exchange->date(), DB::DATETIME_MUTABLE);
        $query->setParameter(':currency', $exchange->currency(), DB::STRING);
        $query->setParameter(':quote', $exchange->quote(), DB::STRING);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(Exchange $exchange): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'exchange.Id as id',
            'exchange.Date as date',
            'exchange.Currency as currency',
            'exchange.Quote as quote',
        ]);
        $query->from('`Exchanges`', '`exchange`');
        $query->where('exchange.Id = :id');
        $query->setParameter(':id', $exchange->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Exchange $exchange): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Exchanges`');

        $query->set('Date', ':date');
        $query->set('Currency', ':currency');
        $query->set('Quote', ':quote');

        $query->setParameter(':date', $exchange->date(), DB::DATETIME_MUTABLE);
        $query->setParameter(':currency', $exchange->currency(), DB::STRING);
        $query->setParameter(':quote', $exchange->quote(), DB::STRING);

        $query->where('Id = :id');
        $query->setParameter(':id', $exchange->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(Exchange $exchange): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Exchanges`');

        $query->where('Id = :id');
        $query->setParameter(':id', $exchange->id(), DB::INTEGER);

        $query->execute();
    }
}
