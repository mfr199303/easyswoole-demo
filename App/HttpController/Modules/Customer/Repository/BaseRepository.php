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
use App\HttpController\Modules\Customer\Model\CustomerPromotion;
use App\HttpController\Modules\Customer\Model\CustomerShop;
use App\HttpController\Modules\Customer\Model\CustomerShopSupport;
use App\HttpController\Modules\Customer\Repository\Interfase\BaseRepositoryInterface;


class BaseRepository implements BaseRepositoryInterface
{
    public function listCustomerByParams($params, $sort = ['customer.id'=>'desc'], $pageSize = null,$page =1)
    {
        $model = Customer::create();
        $model->field($model->getFieldData());

        if (isset($params['is_shop'])) {
            $model->where('is_shop', 1);
        }
        if (isset($params['is_direct_promotion'])) {
            $model->where('is_direct_promotion', 1);
        }
        if (isset($params['is_fission'])) {
            $model->where('is_fission', 1);
        }
        if (isset($params['id'])) {
            $model->where('customer.id', $params['id']);
        }
        if (isset($params['customer_ids'])) {
            $model->where(" customer.id IN(".implode(',',$params['customer_ids']).")");
        }
        if (isset($params['shop_store_id'])) {
            $model->join('customer_shop','customer_shop.customer_id = customer.id');
            $model->where('customer_shop.shop_store_id', $params['shop_store_id']);
        }
        if (isset($params['customer_shop_phone'])) {
            $model->join('customer_shop','customer_shop.customer_id = customer.id');
            $model->where('customer_shop.phone', $params['customer_shop_phone']);
        }
        if (isset($params['support_user_account_id'])) {
            $model->join('customer_shop_support','customer_shop_support.customer_id = customer.id');
            $model->where('customer_shop_support.support_user_account_id', $params['support_user_account_id']);
        }
        if (isset($params['support_user_account_ids'])) {
            $model->join('customer_shop_support','customer_shop_support.customer_id = customer.id');
            $model->where(" customer_shop_support.support_user_account_id IN(".implode(',',$params['support_user_account_ids']).")");
        }

        foreach ($sort as $key=>$value) {
            $model->order($key, $value);
        }

        if ($pageSize) {
            $model->limit($pageSize * ($page-1),$pageSize)->withTotalCount();
            $data = $model->select();
            return [ 'page' => $page , 'page_size' => $pageSize , 'data' => $data ];
        } else {
            return $model->select();
        }
    }

    public function listCustomerProductWeixinByParams($params, $sort = ['id'=>'desc'], $pageSize = null, $page =1)
    {
        $model = CustomerProductWeixin::create();

        if (isset($params['customer_id'])) {
            $model = $model->where('customer_id', $params['customer_id']);
        }
        if (isset($params['phone'])) {
            $model = $model->where('phone', $params['phone']);
        }
        if (isset($params['support_user_account_id'])) {
            $model = $model->where('support_user_account_id', $params['support_user_account_id']);
        }
        if (isset($params['product_weixin_id'])) {
            $model = $model->where('product_weixin_id', $params['product_weixin_id']);
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
            $returnData = $model->select();
            return [ 'page' => $page , 'page_size' => $pageSize , 'data' => $returnData ];
        } else {
            return $model->select();
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
            $model = $model->where(" customer_id IN(".implode(',',$params['support_user_account_ids']).")");
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






    public function findCustomerProductWeixinByParams($params)
    {
        $model = CustomerProductWeixin::create();

        if (isset($params['customer_id'])) {
            $model->where('customer_id', '=', $params['customer_id']);
        }
        if (isset($params['phone'])) {
            $model->where('phone', '=', $params['phone']);
        }
        if (isset($params['support_user_account_id'])) {
            $model->where('support_user_account_id', '=', $params['support_user_account_id']);
        }
        if (isset($params['product_weixin_id'])) {
            $model->where('product_weixin_id', '=', $params['product_weixin_id']);
        }

        return $model->get();
    }

    public function findCustomerPromotionByParams($params)
    {
        $model = CustomerPromotion::create();

        if (isset($params['customer_id'])) {
            $model->where('customer_id', $params['customer_id']);
        }
        if (isset($params['phone'])) {
            $model->where('phone', $params['phone']);
        }

        return $model->get();
    }

    public function updateCustomerPromotionByData($model, $data)
    {
        $customerPromotion = CustomerPromotion::create()->get($model['id']);
        return $customerPromotion->update($data);
    }

    public function createCustomerByData($data)
    {
        $model = Customer::create($data);
        $res = $model->save();
        return $res;
    }

    public function createCustomerPromotionByData($data)
    {
        $model = CustomerPromotion::create($data);
        $res = $model->save();
        return $res;
    }


}