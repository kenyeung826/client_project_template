<?php
/**
 * User: kenyeung
 * Date: 11/17/2022
 * Time: 2:47 PM
 */

namespace Core\Database;


class ManyToOneRelation extends Builder
{
    protected $parentBuilder;
    protected $column;
    protected $columnRight;
    protected $relatedTable;

    public function __construct(Builder $builder, $column, $relatedTable)
    {
        parent::__construct($builder->getConnection());

        $this->parentBuilder = $builder;
        $this->column = $column;
        $this->relatedTable = $relatedTable;

        $this->columns([$this->column]);
        $this->from($this->relatedTable);
    }
}
