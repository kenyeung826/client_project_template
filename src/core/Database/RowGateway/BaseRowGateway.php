<?php
/**
 * User: kenyeung
 * Date: 11/18/2022
 * Time: 3:34 PM
 */
namespace Core\Database\RowGateway;


use Laminas\Db\RowGateway\RowGateway;

class BaseRowGateway extends RowGateway
{
    protected $acl;

    protected $schema;

    public function __construct($primaryKeyColumn, $table, $adapterOrSql, $acl = null){
        $this->acl = $acl;
        parent::__construct($primaryKeyColumn, $table, $adapterOrSql);
    }

    public function getId()
    {
        return $this->data[$this->primaryKeyColumn[0]];
    }

    public function getTable(){
        return $this->table;
    }




}