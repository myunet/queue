<?php

declare (strict_types=1);

namespace myunet\service;

use myunet\Service;
use Symfony\Component\Process\Process;
use myunet\common\extend\CodeExtend;
use think\App;
use think\Container;

class ProcessService extends Service {

    /**
     * 生成 PHP 指令
     * @param string $args
     * @return string
     */
    public static function php(string $args = ''): string
    {
        return ModuleService::getPhpExec() . ' ' . $args;
    }

    /**
     * 立即执行指令
     * @param string $command 执行指令
     * @param boolean $outarr 返回数组
     * @param ?callable $callable 逐行处理
     * @return string|array
     */
    public static function exec(string $command, bool $outarr = false, ?callable $callable = null)
    {
        $root = Container::getInstance()->make(App::class)->getRootPath();
        $process = Process::fromShellCommandline($command)->setWorkingDirectory($root);
        $process->run(is_callable($callable) ? static function ($type, $text) use ($callable, $process) {
            call_user_func($callable, $process, $type, trim(CodeExtend::text2utf8($text))) === true && $process->stop();
        } : null);
        $output = str_replace("\r\n", "\n", CodeExtend::text2utf8($process->getOutput()));
        return $outarr ? explode("\n", $output) : trim($output);
    }

    /**
     * 输出命令行消息
     * @param string $message 输出内容
     * @param integer $backline 回退行数
     * @return void
     */
    public static function message(string $message, int $backline = 0)
    {
        while ($backline-- > 0) $message = "\033[1A\r\033[K{$message}";
        print_r($message . PHP_EOL);
    }

    /**
     * 判断系统类型 WINDOWS
     * @return boolean
     */
    public static function isWin(): bool
    {
        return PATH_SEPARATOR === ';';
    }

    /**
     * 检查文件是否存在
     * @param string $file 文件路径
     * @return boolean
     */
    public static function isFile(string $file): bool
    {
        try {
            return $file !== '' && is_file($file);
        } catch (\Error|\Exception $exception) {
            try {
                if (self::isWin()) {
                    return self::exec("if exist \"{$file}\" echo 1") === '1';
                } else {
                    return self::exec("if [ -f \"{$file}\" ];then echo 1;fi") === '1';
                }
            } catch (\Error|\Exception $exception) {
                return false;
            }
        }
    }
}