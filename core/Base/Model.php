<?php

namespace Core\Base;

use PDO;
use PDOException;
use PDOStatement;

abstract class Model
{
    protected static $table = '';

    protected static $id = 'id';

    /**
     * Get instance database connection.
     *
     * @return null|PDO
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $host = Support::config('database.host');
            $port = Support::config('database.port');
            $database = Support::config('database.database');
            $username = Support::config('database.username');
            $password = Support::config('database.password');

            $charset = 'utf8';
            $collate = 'utf8_unicode_ci';

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
            ];

            try {
                $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
                $db = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $db;
    }

    /**
     * Get all data.
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();

        $table = static::$table;

        $data = $db->prepare("SELECT * FROM {$table}");
        $data->execute();

        return $data->fetchAll();
    }

    public static function getById($id)
    {
        $db = static::getDB();

        $table = static::$table;
        $key = static::$id;

        $data = $db->prepare("SELECT * FROM {$table} WHERE {$table}.{$key}=:id");
        $data->execute(['id' => $id]);

        return $data->fetch();
    }

    /**
     * Run native query.
     *
     * @param $query
     * @return bool|PDOStatement
     */
    public static function query($query)
    {
        $db = static::getDB();

        return $db->query($query);
    }

    /**
     * Create new record data.
     *
     * @param $data
     * @return bool
     */
    public static function create($data)
    {
        $db = static::getDB();

        $table = static::$table;

        $params = [];
        foreach ($data as $field => $val) {
            $params[] = $field . '=:' . $field;
        }
        $params = implode(',', $params);

        $sql = "INSERT INTO {$table} SET {$params}";

        return $db->prepare($sql)->execute($data);
    }

    /**
     * Update table data.
     *
     * @param $data
     * @param $id
     * @return bool
     */
    public static function update($data, $id)
    {
        $db = static::getDB();

        $table = static::$table;
        $key = static::$id;

        if (!is_array($id)) {
            $id = [$key => $id];
        }

        $conditions = [];
        foreach ($id as $field => $val) {
            $conditions[] = $field . '=:' . $field;
        }
        $conditions = implode(',', $conditions);

        $params = [];
        foreach ($data as $field => $val) {
            $params[] = $field . '=:' . $field;
        }
        $params = implode(',', $params);

        $data = array_merge($data, $id);

        $sql = "UPDATE {$table} SET {$params} WHERE {$conditions}";

        return $db->prepare($sql)->execute($data);
    }

    /**
     * Delete record data.
     *
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        $db = static::getDB();

        $table = static::$table;

        if (!is_array($id)) {
            $id = ['id' => $id];
        }

        $params = [];
        foreach ($id as $field => $val) {
            $params[] = $field . '=:' . $field;
        }
        $params = implode(',', $params);

        $sql = "DELETE FROM {$table} WHERE {$params}";

        return $db->prepare($sql)->execute($id);
    }

}