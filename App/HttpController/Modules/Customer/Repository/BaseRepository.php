<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-12-2
 * Time: 下午4:14
 */

namespace App\HttpController\Modules\Customer\Repository;

use App\HttpController\Modules\Customer\Model\Customer;
use App\HttpController\Modules\Customer\Model\CustomerProductWeixin;
use App\HttpController\Modules\Customer\Model\CustomerShop;
use App\HttpController\Modules\Customer\Model\CustomerShopSupport;
use App\HttpController\Modules\Customer\Repository\Interfase\BaseRepositoryInterface;


class BaseRepository implements BaseRepositoryInterface
{
    public function listCustomerByParams($params, $sort = ['id'=>'desc'], $pageSize = null,$page =1)
    {
       $model = new Customer();

//        $withParams = ['CustomerFission', 'CustomerPromotion', 'CustomerShop', 'CustomerShopSupport'];
//        if (isset($params['with_relations'])) {
//            $model = $model->with(filter_with_relations($params['with_relations'], $withParams));
//        }

        if (isset($params['is_shop'])) {
            $model = $model->where('is_shop', 1);
        }
        if (isset($params['is_direct_promotion'])) {
            $model = $model->where('is_direct_promotion', 1);
        }
        if (isset($params['is_fission'])) {
            $model = $model->where('is_fission', 1);
        }
        if (isset($params['id'])) {
            $model = $model->where('id', $params['id']);
        }
        if (isset($params['customer_ids'])) {
            $model = $model->where(" id IN(".implode(',',$params['customer_ids']).")");
        }
//        if (isset($params['shop_store_id'])) {
//            $model = $model->whereHas('CustomerShop', function ($query) use ($params){
//                $query->where('shop_store_id', '=', $params['shop_store_id']);
//            });
//        }
//        if (isset($params['customer_shop_phone'])) {
//            $model = $model->whereHas('CustomerShop', function ($query) use ($params){
//                $query->where('phone', '=', $params['customer_shop_phone']);
//            });
//        }
//        if (isset($params['support_user_account_id'])) {
//            $model = $model->whereHas('CustomerShopSupport', function ($query) use ($params){
//                $query->where('support_user_account_id', '=', $params['support_user_account_id']);
//            });
//        }
//        if (isset($params['support_user_account_ids'])) {
//            $model = $model->whereHas('CustomerShopSupport', function ($query) use ($params){
//                $query->whereIn('support_user_account_id', $params['support_user_account_ids']);
//            });
//        }

        foreach ($sort as $key=>$value) {
            $model = $model->order($key, $value);
        }

        if ($pageSize) {
            $model = $model->limit($pageSize * ($page-1),$pageSize)->withTotalCount();
            $data = $model->findAll();
            return [ 'page' => $page , 'page_size' => $pageSize , 'data' => $data ];
        } else {
            return $model->findAll();
        }
    }

    public function listCustomerProductWeixinByParams($params, $sort = ['id'=>'desc'], $pageSize = null, $page =1)
    {
        $model = new CustomerProductWeixin();

        if (isset($params['customer_id'])) {
            $model = $model->where('customer_id', '=', $params['customer_id']);
        }
        if (isset($params['phone'])) {
            $model = $model->where('phone', '=', $params['phone']);
        }
        if (isset($params['support_user_account_id'])) {
            $model = $model->where('support_user_account_id', '=', $params['support_user_account_id']);
        }
        if (isset($params['product_weixin_id'])) {
            $model = $model->where('product_weixin_id', '=', $params['product_weixin_id']);
        }
        if (isset($params['support_user_account_ids'])) {
            $model = $model->where(" support_user_account_id IN(".implode(',',$params['support_user_account_ids']).")");
        }
        if (isset($params['product_weixin_ids'])) {
            $model = $model->where(" product_weixin_id IN(".implode(',',$params['product_weixin_ids']).")");
        }
        if (isset($params['customer_ids'])) {
            $model = $model->where(" customer_id IN(".implode(',',$params['customer_ids']).")");
        }

        foreach ($sort as $key=>$value) {
            $model = $model->order($key, $value);
        }
        if ($pageSize) {
            $model = $model->limit($pageSize * ($page-1),$pageSize)->withTotalCount();
            $returnData = $model->findAll();
            return [ 'page' => $page , 'page_size' => $pageSize , 'data' => $returnData ];
        } else {
            return $model->findAll();
        }
    }

    public function listCustomerShopByParams($params, $sort = ['id'=>'desc'], $pageSize = null, $page =1)
    {
        $model = new CustomerShop();

//        if (isset($params['customer_id'])) {
//            $model = $model->where('customer_id', '=', $params['customer_id']);
//        }
//        if (isset($params['phone'])) {
//            $model = $model->where('phone', '=', $params['phone']);
//        }
//        if (isset($params['support_user_account_id'])) {
//            $model = $model->where('support_user_account_id', '=', $params['support_user_account_id']);
//        }
//        if (isset($params['product_weixin_id'])) {
//            $model = $model->where('product_weixin_id', '=', $params['product_weixin_id']);
//        }
        if (isset($params['customer_ids'])) {
//            $model = $model->where(" customer_ids IN(".implode(',',$params['support_user_account_ids']).")");
            $model = $model->where(" customer_id IN(344414, 344415)");
        }
//        if (isset($params['product_weixin_ids'])) {
//            $model = $model->where(" product_weixin_id IN(".implode(',',$params['product_weixin_ids']).")");
//        }
//        if (isset($params['customer_ids'])) {
//            $model = $model->where(" customer_id IN(".implode(',',$params['customer_ids']).")");
//        }
//
//        foreach ($sort as $key=>$value) {
//            $model = $model->order($key, $value);
//        }
        if ($pageSize) {
            $model = $model->limit($pageSize * ($page-1),$pageSize)->withTotalCount();
            $returnData = $model->findAll();
            return [ 'page' => $page , 'page_size' => $pageSize , 'data' => $returnData ];
        } else {
            return $model->findAll();
        }
    }










}