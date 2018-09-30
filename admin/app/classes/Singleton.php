<?php
namespace app\classes;

trait Singleton
{
    /**
     * @var $instance self|null
     */
    private static $instance = null;

    // ЗАКРЫВАЕМ ВОЗМОЖНОСТЬ СОЗДАНИЯ И КЛОНИРОВАНИЯ ОБЪЕКТОВ ВНЕ КЛАССА
    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}