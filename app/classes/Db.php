<?php
namespace app\classes;

/**
 * @filename DB.php
 * набор компонентов для работы с БД (Singleton)
 * @author Любомир Пона
 * @copyright 24.09.2013
 * @updated 25.12.2017
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
    public function sql($query, $params = NULL, $emulate = true)
    {
        $STH = false;
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

}
?>

