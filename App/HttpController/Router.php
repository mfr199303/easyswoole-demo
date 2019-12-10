<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-5
 * Time: 上午10:55
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{

    public function initialize(RouteCollector $routeCollector)
    {
        $routeCollector->addGroup('/customer', function(RouteCollector $routeCollector){

          $routeCollector->addRoute('POST', '/getCustomerList', '/Modules\Customer\Controller/CustomerConteroller/getCustomerList');
          $routeCollector->addRoute('POST', '/getCustomerProductWeixinList', '/Modules\Customer\Controller/CustomerConteroller/getCustomerProductWeixinList');


        });

    }
}