<?php

declare (strict_types=1);

namespace myunet\service;

use think\App;
use think\Container;

class ModuleService extends Service {
    /**
     * 获取运行参数变量
     * @param string $field 指定字段
     * @return string
     */
    public static function getRunVar(string $field): string
    {
        $root = Container::getInstance()->make(App::class)->getRootPath();
        $file = $root . 'vendor/binarys.php';
        if (is_file($file) && is_array($binarys = include $file)) {
            return $binarys[$field] ?? '';
        } else {
            return '';
        }
    }

    /**
     * 获取 PHP 执行路径
     * @return string
     */
    public static function getPhpExec(): string
    {
        static $phpBinary;
        if ($phpBinary) return $phpBinary;
        
        if (ProcessService::isFile($phpExec = self::getRunVar('php'))) {
            $phpBinary = $phpExec;
        } else {
            $phpExec = str_replace('/sbin/php-fpm', '/bin/php', PHP_BINARY);
            $phpExec = preg_replace('#-(cgi|fpm)(\.exe)?$#', '$2', $phpExec);
            $phpBinary = ProcessService::isFile($phpExec) ? $phpExec : 'php';
        }
        
        return $phpBinary;
    }
}