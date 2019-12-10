<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-13
 * Time: 下午2:29
 */

namespace App\HttpController\Process;


use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Coroutine;

class ProcessQueue extends AbstractProcess
{
    protected $data = [];

    //当进程启动后，会执行的回调
    protected function run($arg)
    {
        $size = $arg['size'];
        go(function() use ($size){
           while (true){
               $this->data[] = '哇哈哈哈';
               if (count($this->data) > $size) {
                   echo '不能哇哈哈,要呼哇哈哈哈的笑'.PHP_EOL;
               }
               Coroutine::sleep(1);
           }
        });
    }

    // 当有主进程对子进程发送消息的时候,会触发的回调,记住触发后要使用$process->read()来读取消息
    protected function onPipeReadable(\Swoole\Process $process)
    {
        $content = $process->read();
        switch ($content) {
            case 'clear':
               $this->data = [];
              break;
            default:
              echo '没有操作';
        }
    }


    // 当该进程退出的时候，会执行该回调
    protected function onShutDown()
    {

    }

    // 当该进程出现异常的时候，会执行该回调
    protected function onException(\Throwable $throwable, ...$args)
    {

    }

}