<?php

declare (strict_types=1);

namespace myunet\common\extend;

class CodeExtend {

    /**
     * 生成日期编码
     * @param integer $size 编码长度
     * @param string $prefix 编码前缀
     * @return string
     */
    public static function uniqidDate(int $size = 16, string $prefix = ''): string
    {
        if ($size < 14) $size = 14;
        $code = $prefix . date('Ymd') . (date('H') + date('i')) . date('s');
        while (strlen($code) < $size) $code .= rand(0, 9);
        return $code;
    }

    /**
     * 文本转码
     * @param string $text 文本内容
     * @param string $target 目标编码
     * @return string
     */
    public static function text2utf8(string $text, string $target = 'UTF-8'): string
    {
        [$first2, $first4] = [substr($text, 0, 2), substr($text, 0, 4)];
        if ($first4 === chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF)) $ft = 'UTF-32BE';
        elseif ($first4 === chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00)) $ft = 'UTF-32LE';
        elseif ($first2 === chr(0xFE) . chr(0xFF)) $ft = 'UTF-16BE';
        elseif ($first2 === chr(0xFF) . chr(0xFE)) $ft = 'UTF-16LE';
        return mb_convert_encoding($text, $target, $ft ?? mb_detect_encoding($text));
    }
}