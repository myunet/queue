<?php

if (!function_exists('syspath')) {
    /**
     * 获取文件绝对路径
     * @param string $name 文件路径
     * @param ?string $root 程序根路径
     * @return string
     */
    function syspath(string $name = '', ?string $root = null): string
    {
        if (is_null($root)) $root = app()->getRootPath();
        $attr = ['/' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR];
        return rtrim($root, '\\/') . DIRECTORY_SEPARATOR . ltrim(strtr($name, $attr), '\\/');
    }
}

if (!function_exists('sysvar')) {
    /**
     * 读写单次请求的内存缓存
     * @param null|string $name 数据名称
     * @param null|mixed $value 数据内容
     * @return null|array|mixed 返回内容
     */
    function sysvar(?string $name = null, $value = null)
    {
        static $swap = [];
        if ($name === '' && $value === '') {
            return $swap = [];
        } elseif (is_null($value)) {
            return is_null($name) ? $swap : ($swap[$name] ?? null);
        } else {
            return $swap[$name] = $value;
        }
    }
}



if (!function_exists('format_datetime')) {
    /**
     * 日期格式标准输出
     * @param int|string $datetime 输入日期
     * @param string $format 输出格式
     * @return string
     */
    function format_datetime($datetime, string $format = 'Y年m月d日 H:i:s'): string
    {
        if (empty($datetime)) {
            return '-';
        } elseif (is_numeric($datetime)) {
            return date(lang($format), intval($datetime));
        } elseif ($timestamp = strtotime((string)$datetime)) {
            return date(lang($format), $timestamp);
        } else {
            return (string)$datetime;
        }
    }
}

if (!function_exists('trace_file')) {
    /**
     * 输出异常数据到文件
     * @param Exception $exception
     * @return boolean
     */
    function trace_file(Exception $exception): bool
    {
        $path = app()->getRuntimePath() . 'trace';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $name = substr($exception->getFile(), strlen(syspath()));
        $file = $path . DIRECTORY_SEPARATOR . date('Ymd_His_') . strtr($name, ['/' => '.', '\\' => '.']);
        $json = json_encode($exception instanceof \myunet\common\exception\Exception ? $exception->getData() : [], 64 | 128 | 256);
        $class = get_class($exception);
        return false !== file_put_contents($file,
                "[CODE] {$exception->getCode()}" . PHP_EOL .
                "[INFO] {$exception->getMessage()}" . PHP_EOL .
                ($exception instanceof \myunet\common\exception\Exception ? "[DATA] {$json}" . PHP_EOL : '') .
                "[FILE] {$class} in {$name} line {$exception->getLine()}" . PHP_EOL .
                "[TIME] " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL .
                '[TRACE]' . PHP_EOL . $exception->getTraceAsString()
            );
    }
}