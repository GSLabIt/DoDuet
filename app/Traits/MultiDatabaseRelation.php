<?php

namespace App\Traits;

trait MultiDatabaseRelation
{
    /**
     * @param string $new_connection
     * @param callable $fn
     * @param bool $restore_last
     * @return mixed
     */
    public function multiDatabaseRunQuery(string $new_connection, callable $fn, bool $restore_last = true): mixed
    {
        // retrieve the current connection name
        $original_connection_name = $this->getConnectionName();

        // connect to the new db
        $this->setConnection($new_connection);

        // retrieve the relation
        $result = $fn();

        // restore the connection to the default value
        if($restore_last) {
            $this->setConnection($original_connection_name);
        }

        // return the result of the relation computation
        return $result;
    }
}
