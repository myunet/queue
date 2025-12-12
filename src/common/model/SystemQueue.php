<?php

declare (strict_types=1);

namespace myunet\common\model;

use think\Model;

class SystemQueue extends Model
{
    protected $name = 'system_queue_test';
    protected $createTime = 'create_at';
    protected $updateTime = false;

    /**
     * 格式化日期时间
     * @param mixed $value
     * @return string
     */
    private function formatDatetime($value): string
    {
        if (empty($value)) return '';
        return date('Y-m-d H:i:s', is_numeric($value) ? $value : strtotime($value));
    }

    /**
     * 格式化计划时间
     * @param mixed $value
     * @return string
     */
    public function getExecTimeAttr($value): string
    {
        return $this->formatDatetime($value);
    }

    /**
     * 执行开始时间处理
     * @param mixed $value
     * @return string
     */
    public function getEnterTimeAttr($value): string
    {
        return floatval($value) > 0 ? $this->formatDatetime(intval($value)) : '';
    }

    /**
     * 执行结束时间处理
     * @param mixed $value
     * @param array $data
     * @return string
     */
    public function getOuterTimeAttr($value, array $data): string
    {
        if ($value > 0 && $value > $data['enter_time']) {
            return sprintf("耗时 %.4f 秒", $data['outer_time'] - $data['enter_time']);
        } else {
            return ' - ';
        }
    }

    /**
     * 格式化创建时间
     * @param mixed $value
     * @return string
     */
    public function getCreateAtAttr($value): string
    {
        return $this->formatDatetime($value);
    }
}