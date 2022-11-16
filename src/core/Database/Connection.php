<?php
/**
 * User: kenyeung
 * Date: 11/14/2022
 * Time: 3:14 PM
 */
namespace Core\Database;
use Laminas\Db\Adapter\Adapter;

class Connection extends Adapter
{

    public function isStrictModeEnabled()
    {
        $enabled = false;
        $driverName = $this->getDriver()->getDatabasePlatformName();

        switch (strtolower($driverName)) {
            case 'mysql':
                $enabled = $this->isMySQLStrictModeEnabled();
                break;
        }

        return $enabled;
    }

    protected function isMySQLStrictModeEnabled()
    {
        $strictModes = ['STRICT_ALL_TABLES', 'STRICT_TRANS_TABLES'];
        $statement = $this->query('SELECT @@sql_mode as modes');
        $result = $statement->execute();
        $modesEnabled = $result->current();

        $modes = explode(',', $modesEnabled['modes']);
        foreach ($modes as $name) {
            $modeName = strtoupper(trim($name));
            if (in_array($modeName, $strictModes)) {
                return true;
            }
        }

        return false;
    }
    public function connect()
    {
        return call_user_func_array([$this->getDriver()->getConnection(), 'connect'], func_get_args());
    }

    public function execute($sql)
    {
        return $this->query($sql, static::QUERY_MODE_EXECUTE);
    }
}