<?php
namespace app\classes;

/**
 * @filename DB.php
 * набор компонентов для работы с БД (PDO Singleton)
 * @author Любомир Пона
 * @copyright 24.09.2013
 * @updated 29.09.2018
 */

class Db extends Config
{
    /**
     * @var $DBH \PDO
     * идентефикатор соединения,
     * @var $DSN string
     * для подключения к БД.
    ---------------------------
     * @var $OPT array
     * дополнительные параметры.
     */
    private static
        $DBH,
        $DSN = "mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME.";charset=".self::SQLCHARSET,

        $OPT = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ];

    // Используем технологию Singleton
    use Singleton;

    // При создании объекта вызываем метод open_connection
    private function __construct()
    {
        $this->open_connection();
    }

    // соединяемся с БД
    private function open_connection()
    {
        try
        {
            self::$DBH = new \PDO(self::$DSN, self::DB_USER, self::DB_PASS, self::$OPT);
        }
        catch(\PDOException $e)
        {
            echo "Извините, но операция подключения к БД не может быть выполнена";
            file_put_contents('DBlogs.txt',$e->getMessage()."\n",FILE_APPEND);
        }
    }

    // реализация запроса к БД
    private function sql($query, $params = NULL, $emulate = true)
    {
        try
        {
            // если вместе с запросом был передан массив с данными
            if ($params!=NULL)
            {
                $STH = self::$DBH->prepare($query);

                self::$DBH->setAttribute(\PDO::ATTR_EMULATE_PREPARES, $emulate);
                $STH->execute($params);
            }
            else
            {
                $STH = self::$DBH->query($query);
            }
        }
        catch(\PDOException $e)
        {
            echo "Извините, но операция не может быть выполнена";
            $error = date("j.m.Y \at G:i:s") . "  -  ".
                $e->getMessage() . "\n";
            // пишем все ошибки в файл с логами
            file_put_contents('DBlogs.txt', $error, FILE_APPEND);
        }

        return $STH;
    }

    // CRUD methods
    //
    public function create()
    {

    }
    //
    public function read($table, $cols = "*", $where = NULL)
    {
        $sql = "SELECT ";
        if (is_array($cols))
        {
            foreach ($cols as $col)
            {
                $sql.= $col.", ";
            }
        }
        else
        {
            $sql.= $cols;
        }
        $sql.= " FROM {$table}";
        //
        if ($where !== null)
        {
            $sql.= " WHERE ";
            foreach ($where as $col => $value)
            {
                $sql.= "{$col} = :{$col}, ";
            }
            $sql = substr($sql, 0, -2);
        }
        //
        $STH = $this->sql($sql, $where);
        return $STH;
    }
    //
    public function update($table, $data, $where = NULL)
    {
        $sql = "UPDATE {$table} SET ";
        foreach ($data as $k=>$v)
        {
            $sql.= "{$k}=:{$k}, ";
        }

        $sql = substr($sql,0,-2);

        if($where)
        {
            foreach ($where as $col=>$value)
            {
                $sql.= " WHERE {$col}='{$value}'";
            }
        }

        $this->sql($sql, $data);
    }
    //
    public function delete()
    {

    }
}
?>