<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-12-7
 * Time: 下午5:37
 */

namespace App\HttpController\Builders;

use App\HttpController\BaseBuilders;
use App\HttpController\Modules\Customer\Repository\BaseRepository;

class CustomerShopBuilder extends BaseBuilders
{
    public $baseRepository;

    public function __construct()
    {
        $this->baseRepository = new BaseRepository();
    }

    public function find($params)
    {

    }

    public function getList($temp)
    {
        $params = isset($temp['params']) ?$temp['params'] :[];
        $orderType = isset($temp['order_type']) ?$temp['order_type'] :[];
        $pageSize = isset($temp['page_size']) ?$temp['page_size'] :0;
        $page = isset($temp['page']) ?$temp['page'] :0;

        return $this->get($this->baseRepository->listCustomerShopByParams($params,$orderType,$pageSize,$page));
    }

}