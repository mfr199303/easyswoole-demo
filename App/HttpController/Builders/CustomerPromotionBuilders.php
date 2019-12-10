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

class CustomerPromotionBuilders extends BaseBuilders
{
     public $baseRepository;

     public function __construct(BaseRepository $repository)
     {
         $this->baseRepository = $repository;
     }
    public function find($params)
    {

    }

    public function getList($params)
    {
        return $this->get($this->baseRepository->listCustomerByParams($params));
    }

//    public function ProductGoods()
//    {
//        return $this->matchOne('App\HttpConteroller\Builders\CustomerBuilders', 'product_goods_id', 'id');
//    }

}