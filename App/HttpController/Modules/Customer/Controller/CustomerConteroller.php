<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-24
 * Time: 下午3:23
 */

namespace App\HttpController\Modules\Customer\Controller;

use App\HttpController\Builders\CustomerBuilder;
use App\HttpController\Builders\CustomerShopBuilder;
use App\HttpController\Modules\Customer\Repository\BaseRepository;
use App\HttpController\publicCallController;
use EasySwoole\Http\Message\Status;
use EasySwoole\Mysqli\Mysqli;
use App\HttpController\RedisHandler;
use EasySwoole\ORM\DbManager;

class CustomerConteroller extends publicCallController
{
    public $baseRepository;

    protected function onRequest(?string $action): ?bool
    {
        $this->baseRepository = new BaseRepository();
        return true;
    }

//    public function index()
//    {
//        $customer = new Customer();
//        $data = $customer->where('id', 1)->get();;

//        $customer = $customer::create([
//            'is_shop' =>1 , 'is_direct_promotion'=>2 , 'is_fission'=>11
//        ]);
//        $res = $customer->save();

//        $res = $customer->destroy(344397);

//        $res = $customer->update(['is_fission' => 0 ], ['id' => 344396]);
//
//        $this->writeJson(Status::CODE_OK,$res,'执行成功');
//        return;
//
//        if (empty($returnData)) {
//            $this->writeJson(Status::CODE_MULTIPLE_CHOICES,null,'执行失败');
//            return;
//        }
//
//        $this->writeJson(Status::CODE_OK,$returnData->toArray(),'执行成功');
//    }

    public function getCustomerList()
    {
        $request = $this->request();
        $params = [];

        $isShop = $request->getRequestParam('is_shop');
        if ($isShop) {
            $params['is_shop'] = $isShop;
        }
        $isDirectPromotion = $request->getRequestParam('is_direct_promotion');
        if ($isDirectPromotion) {
            $params['is_direct_promotion'] = $isDirectPromotion;
        }
        $isFission = $request->getRequestParam('is_fission');
        if ($isFission) {
            $params['is_fission'] = $isFission;
        }
        $phone = $request->getRequestParam('customer_shop_phone');
        if ($phone) {
            $params['customer_shop_phone'] = $phone;
        }
        $shopStoreId = $request->getRequestParam('shop_store_id');
        if ($shopStoreId) {
            $params['shop_store_id'] = $shopStoreId;
        }
        $customerIds = $request->getRequestParam('customer_ids');
        if (is_array($customerIds)) {
            $params['customer_ids'] = $customerIds;
        }
        $supportUserAccountIds = $request->getRequestParam('support_user_account_ids');
        if (is_array($supportUserAccountIds)) {
            $params['support_user_account_ids'] = $supportUserAccountIds;
        }

        $temp = [];
        $temp['params'] = $params;
        $temp['order_type'] = $this->handleSort();
        $temp['page_size'] = intval($request->getRequestParam('page_size'));
        $temp['page'] = $this->handlePage();

        $customerBuilder = new CustomerBuilder();
        $withRelations = $request->getRequestParam('with_relations');
        $withParams = ['CustomerShopSupport','CustomerFission','CustomerPromotion','CustomerShop'];
        if (!empty($withRelations) || is_array($withRelations) || count($withRelations) > 0) {
            $customerBuilder->with($this->filter_with_relations($withRelations, $withParams));
        }
        $returnData = $customerBuilder->getList($temp);
        return $this->success($returnData);
    }

    public function getCustomerProductWeixinList()
    {
        $request = $this->request();
        $params = [];

        $phone = $request->getRequestParam('phone');
        if ($phone) {
            $params['phone'] = $phone;
        }
        $supportUserAccountId = $request->getRequestParam('support_user_account_id');
        if ($supportUserAccountId) {
            $params['support_user_account_id'] = $supportUserAccountId;
        }
        $productWeixinId = $request->getRequestParam('product_weixin_id');
        if ($productWeixinId) {
            $params['product_weixin_id'] = $productWeixinId;
        }
        $customerIds = $request->getRequestParam('customer_ids');
        if (is_array($customerIds)) {
            $params['customer_ids'] = $customerIds;
        }
        $supportUserAccountIds = $request->getRequestParam('support_user_account_ids');
        if (is_array($supportUserAccountIds)) {
            $params['support_user_account_ids'] = $supportUserAccountIds;
        }

        $orderType = $this->handleSort();
        $page = $this->handlePage();
        $pageSize = intval($request->getRequestParam('page_size'));

        $returnData = $this->baseRepository->listCustomerProductWeixinByParams($params, $orderType, $pageSize ,$page);

        return $this->success($returnData);
    }

    public function getCustomerProductWeixinInfo()
    {
        $request = $this->request();
        $params = [];

        $phone = $request->getRequestParam('phone');
        if ($phone) {
            $params['phone'] = $phone;
        }
        $supportUserAccountId = $request->getRequestParam('support_user_account_id');
        if ($supportUserAccountId) {
            $params['support_user_account_id'] = $supportUserAccountId;
        }
        $productWeixinId = $request->getRequestParam('product_weixin_id');
        if ($productWeixinId) {
            $params['product_weixin_id'] = $productWeixinId;
        }
        $customerIds = $request->getRequestParam('customer_ids');
        if (is_array($customerIds)) {
            $params['customer_ids'] = $customerIds;
        }
        $supportUserAccountIds = $request->getRequestParam('support_user_account_ids');
        if (is_array($supportUserAccountIds)) {
            $params['support_user_account_ids'] = $supportUserAccountIds;
        }

        $returnData = $this->baseRepository->findCustomerProductWeixinByParams($params);
        if (empty($returnData)){
            return $this->success([]);
        }
        return $this->success($returnData);
    }

    public function getAndCreateCustomerPromotionInfo()
    {
        $request = $this->request();
        $phone =$request->getRequestParam('phone');
        $productWeixinId = $request->getRequestParam('product_weixin_id');
        if (!$phone || !$productWeixinId) {
            return $this->error(1000,'缺少参数');
        }
        $supportUserAccountId = $request->getRequestParam('support_user_account_id');
        $promotionUserAccountId = $request->getRequestParam('promotion_user_account_id');
        $weixinAccount =$request->getRequestParam('weixin_account');
        $promotionChannelId = $request->getRequestParam('promotion_channel_id');
        $productId = $request->getRequestParam('product_id');
        $joinTime = $request->getRequestParam('join_time');
        $joinTime = $joinTime ? date('Y-m-d',strtotime($joinTime)) : null;
        $customerPromotionTemp = [ 'phone' => $phone, 'product_weixin_id' => $productWeixinId ];
        $hasCustomerPromotion = $this->baseRepository->findCustomerPromotionByParams($customerPromotionTemp);
        try {
            DbManager::getInstance()->startTransaction();
            if ($hasCustomerPromotion) {
                return $this->success($this->baseRepository->updateCustomerPromotionByData($hasCustomerPromotion, [
                    'support_user_account_id' => $supportUserAccountId,
                    'promotion_user_account_id' => $promotionUserAccountId,
                    'weixin_account' => $weixinAccount,
                    'promotion_channel_id' => $promotionChannelId,
                    'product_id' => $productId,
                    'join_time' => $joinTime
                ]));
            }
            $customer = $this->baseRepository->createCustomerByData([
                'is_direct_promotion' => 1
            ]);
            $customerInfo = $this->baseRepository->createCustomerPromotionByData([
                'customer_id' => $customer->id,
                'phone' => $phone,
                'product_weixin_id' => $productWeixinId,
                'support_user_account_id' => $supportUserAccountId,
                'promotion_user_account_id' => $promotionUserAccountId,
                'weixin_account' => $weixinAccount,
                'promotion_channel_id' => $promotionChannelId,
                'product_id' => $productId,
                'join_time' => $joinTime
            ]);
            DbManager::getInstance()->commit();

            return $this->success($customerInfo);
        } catch (\Exception $e) {
            DbManager::getInstance()->rollback();
            return $this->error(3000);
        }
    }














}