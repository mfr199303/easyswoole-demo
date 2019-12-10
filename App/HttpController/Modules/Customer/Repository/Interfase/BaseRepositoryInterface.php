<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-12-2
 * Time: 下午4:13
 */

namespace App\HttpController\Modules\Customer\Repository\Interfase;


interface BaseRepositoryInterface
{
    public function listCustomerByParams($params, $sort = ['id'=>'desc'], $pageSize = null,$page =1);

    public function listCustomerProductWeixinByParams($params, $sort = ['id'=>'desc'], $pageSize = null,$page =1);

    public function listCustomerShopByParams($params, $sort = ['id'=>'desc'], $pageSize = null,$page =1);

}