<?php
/**
 * User: kenyeung
 * Date: 11/17/2022
 * Time: 2:51 PM
 */
namespace Core\Database\TableGateway;
use Core\Database\RowGateway\BaseRowGateway;
use Laminas\Db\Adapter\AdapterInterface;

use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\Feature\AbstractFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;


class BaseTableGateway extends \Laminas\Db\TableGateway\TableGateway
{
    public $primaryKeyFieldName = null;

    public $memcache;

    public $column;

    protected $acl = null;

    public function __construct($table, AdapterInterface $adapter, $acl = null, $features = null, $resultSetPrototype = null, $sql = null, $primaryKeyName = null)
    {
        $this->$acl = $acl;

        if ($this->primaryKeyFieldName === null) {
            if ($primaryKeyName !== null) {
                $this->primaryKeyFieldName = $primaryKeyName;
            }
        }
        if ($features === null) {
            $features = new FeatureSet();
        } else if ($features instanceof AbstractFeature) {
            $features = [$features];
        } else if (is_array($features)) {
            $features = new FeatureSet($features);
        }
        if ($this->primaryKeyFieldName) {
            $rowGatewayPrototype = new BaseRowGateway($this->primaryKeyFieldName, $table, $adapter, $this->acl);
            $rowGatewayFeature = new RowGatewayFeature($rowGatewayPrototype);
            $features->addFeature($rowGatewayFeature);
        }

        parent::__construct($table, $adapter, $features, $resultSetPrototype, $sql);
    }


    public function fetchAll ($selectModifier) {
        return $this->select(function (Select $select) use ($selectModifier) {
            if (is_callable($selectModifier)) {
                $selectModifier($select);
            }
        });
    }

    public function fetchAllByKey($key, $selectModifier = null) {
       $record = $this->fetchAll($selectModifier)->toArray();
       return $this->withKey($key, $record);
    }


    public function findByKey($id, $pkFieldName = null) {
        if ($pkFieldName == null) {
            $pkFieldName = $this->primaryKeyFieldName;
        }

        $record = $this->findOneBy($pkFieldName, $id);

        return $record;
    }

    public function findOneBy($field, $value){
        $rowset = $this->select(function (Select $select) use ($field, $value) {
            $select->limit(1);
            $select->where->equalTo($field, $value);
        });

        $row = $rowset->current();
        // Supposing this "one" doesn't exist in the DB
        if (!$row) {
            return false;
        }

        return $row->toArray();
    }

    public function findOneByArray(array $data)
    {
        $rowset = $this->select($data);

        $row = $rowset->current();
        // Supposing this "one" doesn't exist in the DB
        if (!$row) {
            return false;
        }

        return $row->toArray();
    }


    public function ignoreFilters()
    {
        $this->options['filter'] = false;

        return $this;
    }

    public function withKey($key, $record) {
        $withKey = [];
        foreach ($record as $row) {
            $keyStr = "";
            if (is_array($key)) {
                foreach ($key as $k) {
                    $keyStr .= '-'.$row[$k];
                }
                if (strlen($keyStr) > 0 ) {
                    $keyStr = substr($keyStr, 1);
                }
            } else {
                $keyStr = $row[$key];
            }
            $withKey[$keyStr] = $row;
        }
        return $withKey;
    }


}