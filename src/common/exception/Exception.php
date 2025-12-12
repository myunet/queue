<?php

declare (strict_types=1);

namespace myunet\common\exception;

/**
 * 自定义数据异常
 * @class Exception
 * @package myunet\common\exception
 */
class Exception extends \Exception
{
    /**
     * 异常数据对象
     * @var mixed
     */
    protected $data = [];

    /**
     * Exception constructor.
     * @param string $message
     * @param integer $code
     * @param mixed $data
     */
    public function __construct(string $message = "", int $code = 0, $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * 获取异常停止数据
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置异常停止数据
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}