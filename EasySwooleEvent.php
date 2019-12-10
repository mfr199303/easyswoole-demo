<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Crontab\TaskOne;
use App\Middleware\CORSMiddleware;
use App\Middleware\TokenValidationMiddleware;
use App\HttpController\Process\ProcessQueue;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Component\Process\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\WordsMatch\WordsMatchServer;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {

        /* 这是注册orm模型的部分 */
        $ormConfig = new \EasySwoole\ORM\Db\Config();
        $ormConfig->setDatabase('baizhitang_customer');
        $ormConfig->setUser('root');
        $ormConfig->setPassword('root');
        $ormConfig->setHost('192.168.18.100');

        // 因为orm自带连接池 , 所有可以配置连接池配置
        $ormConfig->setGetObjectTimeout(3.0);
        $ormConfig->setGetObjectTimeout(3.0); //设置获取连接池对象超时时间
        $ormConfig->setIntervalCheckTime(30*1000); //设置检测连接存活执行回收和创建的周期
        $ormConfig->setMaxIdleTime(15); //连接池对象最大闲置时间(秒)
        $ormConfig->setMaxObjectNum(20); //设置最大连接池存在连接对象数量
        $ormConfig->setMinObjectNum(5); //设置最小连接池存在连接对象数量

        DbManager::getInstance()->addConnection(new Connection($ormConfig));

//        Crontab::getInstance()->addTask(TaskOne::class);

    }

    public static function onRequest(Request $request, Response $response): bool
    {

        CORSMiddleware::getInstance()->handle($request,$response);
//        TokenValidationMiddleware::getInstance()->handle($request,$response);

        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}