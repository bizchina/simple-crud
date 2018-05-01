<?php
declare(strict_types = 1);

namespace SimpleCrud\Engine\Common\Query;

use PDO;
use SimpleCrud\Table;
use function Latitude\QueryBuilder\fn;

trait AggregationTrait
{
    use QueryTrait;
    use WhereTrait;

    private $field;

    public function __construct(Table $table, string $field = 'id')
    {
        $this->init($table);
        $this->field = $field;

        $this->query = $this->builder
            ->select(fn(self::AGGREGATION_FUNCTION, $field))
            ->from($table->getName());
    }

    public function run()
    {
        $statement = $this->__invoke();
        $statement->setFetchMode(PDO::FETCH_NUM);

        $result = $statement->fetch();
        $field = $this->table->{$this->field};

        return $field->rowValue($result[0]);
    }
}