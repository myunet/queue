<?php


declare (strict_types=1);

namespace myunet;

use think\App;
use think\Container;

/**
 * 自定义服务基类
 * @class Service
 * @package myunet
 */
abstract class Service
{
    /**
     * 应用实例
     * @var App
     */
    protected $app;

    /**
     * Constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->initialize();
    }

    /**
     * 初始化服务
     */
    protected function initialize()
    {
    }

    /**
     * 静态实例对象
     * @param array $var 实例参数
     * @param boolean $new 创建新实例
     * @return static|mixed
     */
    public static function instance(array $var = [], bool $new = false)
    {
        return Container::getInstance()->make(static::class, $var, $new);
    }
}