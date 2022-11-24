<?php
/**
 * User: kenyeung
 * Date: 11/17/2022
 * Time: 2:47 PM
 */

namespace Core\Database;


use Laminas\Db\Sql\Expression;

class ManyToManyRelation extends Builder
{
    protected $parentBuilder;
    protected $table;
    protected $columnLeft;
    protected $columnRight;

    public function __construct(Builder $builder, $table, $columnLeft, $columnRight)
    {
        parent::__construct($builder->getConnection());

        $this->parentBuilder = $builder;
        $this->table = $table;
        $this->columnLeft = $columnLeft;
        $this->columnRight = $columnRight;

        $this->from($table);
    }

    public function all(array $values)
    {
        $this->columns([$this->columnLeft]);
        $this->whereIn($this->columnRight, $values);
        $this->groupBy($this->columnLeft);
        $this->having(new Expression('COUNT(*) = ?', count($values)));

        return $this;
    }

    public function has($count = 1)
    {
        $this->columns([$this->columnLeft]);
        $this->groupBy($this->columnLeft);
        $this->having(new Expression('COUNT(*) >= ?', (int) $count));

        return $this;
    }
}