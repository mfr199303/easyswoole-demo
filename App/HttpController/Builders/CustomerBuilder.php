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

class CustomerBuilder extends BaseBuilders
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

        return $this->get($this->baseRepository->listCustomerByParams($params,$orderType,$pageSize,$page));
     }

//     public function CustomerShopSupport()
//     {
//        return $this->matchOne('App\HttpConteroller\Builders\CustomerShopSupportBuilders', 'id', 'customer_id');
//     }

//     public function CustomerFission()
//     {
//        return $this->matchOne('App\HttpConteroller\Builders\CustomerFissionBuilders', 'id', 'customer_id');
//     }

//     public function CustomerPromotion()
//     {
//        return $this->matchOne('App\HttpConteroller\Builders\CustomerPromotionBuilders', 'id', 'customer_id');
//     }

     public function CustomerShop()
     {
        return $this->matchOne('App\HttpController\Builders\CustomerShopBuilder', 'id', 'customer_id','customer_ids');
     }

}