<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-27
 * Time: 下午5:02
 */

namespace App\Crontab;


use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TaskOne extends AbstractCronTask
{
    public static function getRule(): string
    {
        // TODO: Implement getRule() method.
        // 定时周期 （每两分钟一次）
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        // TODO: Implement getTaskName() method.
        // 定时任务名称
        return 'taskOne';
    }

    function run(int $taskId, int $workerIndex)
    {
        // TODO: Implement run() method.
        // 定时任务处理逻辑
        var_dump('run once every two minutes');
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        echo $throwable->getMessage();
    }

}