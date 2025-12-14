<?php

declare (strict_types=1);

namespace myunet;

class Library extends \think\Service {
    /**
     * 启动服务
     * @return void
     */
    public function boot() {

        $this->commands([
            \myunet\queue\Queue::class
        ]);
    }

    /**
     * 初始化服务
     * @return void
     */
    public function register() {

    }
}