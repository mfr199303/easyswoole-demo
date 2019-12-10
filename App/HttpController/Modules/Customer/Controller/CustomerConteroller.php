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
        $withParams = ['CustomerShop'];
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
//        $withRelations = Input::get('with_relations', []);
//        if (is_array($withRelations) && $withRelations) {
//            $params['with_relations'] = $withRelations;
//        }
        $orderType = $this->handleSort();
        $page = $this->handlePage();
        $pageSize = intval($request->getRequestParam('page_size'));

        $returnData = $this->baseRepository->listCustomerProductWeixinByParams($params, $orderType, $pageSize ,$page);

        return $this->success($returnData);
    }

    // 下面继续写
    public function getCustomerProductWeixinInfo()
    {
        $params = [];

        $phone = Input::get('phone', null);
        if ($phone) {
            $params['phone'] = $phone;
        }
        $supportUserAccountId = Input::get('support_user_account_id', null);
        if ($supportUserAccountId) {
            $params['support_user_account_id'] = $supportUserAccountId;
        }
        $productWeixinId = Input::get('product_weixin_id', null);
        if ($productWeixinId) {
            $params['product_weixin_id'] = $productWeixinId;
        }
        $customerIds = Input::get('customer_ids', null);
        if (is_array($customerIds)) {
            $params['customer_ids'] = $customerIds;
        }
        $supportUserAccountIds = Input::get('support_user_account_ids', null);
        if (is_array($supportUserAccountIds)) {
            $params['support_user_account_ids'] = $supportUserAccountIds;
        }
        $withRelations = Input::get('with_relations', []);
        if (is_array($withRelations) && $withRelations) {
            $params['with_relations'] = $withRelations;
        }

        $returnData = $this->customerRepository->findCustomerProductWeixinByParams($params);

        return $this->success($returnData);
    }
















}