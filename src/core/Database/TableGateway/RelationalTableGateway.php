<?php
/**
 * User: kenyeung
 * Date: 11/17/2022
 * Time: 3:33 PM
 */
namespace Core\Database\TableGateway;

use Core\Exception\CoreException;

class RelationalTableGateway extends BaseTableGateway
{

    /**
     * @var array
     */
    protected $defaultEntriesSelectParams = [
        'limit' => 20,
        'offset' => 0,
        'search' => null,
        'meta' => 0,
        'status' => null
    ];

    protected $operatorShorthand = [
        'eq' => ['operator' => 'equal_to', 'not' => false],
        '='  => ['operator' => 'equal_to', 'not' => false],
        'neq' => ['operator' => 'equal_to', 'not' => true],
        '!='  => ['operator' => 'equal_to', 'not' => true],
        '<>'  => ['operator' => 'equal_to', 'not' => true],
        'lt' => ['operator' => 'less_than', 'not' => false],
        '<' => ['operator' => 'less_than', 'not' => false],
        'lte' => ['operator' => 'less_than_or_equal', 'not' => false],
        '<=' => ['operator' => 'less_than_or_equal', 'not' => false],
        'gt' => ['operator' => 'greater_than', 'not' => false],
        '>' => ['operator' => 'greater_than', 'not' => false],
        'gte' => ['operator' => 'greater_than_or_equal', 'not' => false],
        '>=' => ['operator' => 'greater_than_or_equal', 'not' => false],
        'in' => ['operator' => 'in', 'not' => false],
        'nin' => ['operator' => 'in', 'not' => true],

        'nlike' => ['operator' => 'like', 'not' => true],
        'contains' => ['operator' => 'like'],
        'ncontains' => ['operator' => 'like', 'not' => true],

        'rlike' => ['operator' => 'like'],
        'nrlike' => ['operator' => 'like', 'not' => true],

        'nnull' => ['operator' => 'null', 'not' => true],

        'nempty' => ['operator' => 'empty', 'not' => true],

        'nhas' => ['operator' => 'has', 'not' => true],

        'nbetween' => ['operator' => 'between', 'not' => true],
    ];

    public function deleteRecord($id, array $params = [])
    {
        $conditions = [
            $this->primaryKeyFieldName => $id
        ];

        // TODO: Add "item" hook, different from "table" hook
        $success = $this->delete($conditions);

        if (!$success) {
            throw new CoreException(
                sprintf('Error deleting a record in %s with id %s', $this->table, $id)
            );
        }
    }

}