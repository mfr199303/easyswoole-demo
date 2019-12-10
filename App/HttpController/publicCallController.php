<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-12-2
 * Time: 下午4:23
 */

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;

class publicCallController extends Controller
{
    public function index()
    {
        // TODO: Implement index() method.
    }

    public function handleSort() {
        $request = $this->request();
        $sort = trim($request->getRequestParam('sort'));
        if ($sort == null){
            $sort = '-id';
        }
        if (!$sort) {
            return [];
        }

        $operation = substr($sort, 0, 1);
        $operation = ($operation=='+')?'asc':'desc';
        $sort = substr($sort, 1);
        $orderBy['sort'] = $sort;
        return [ $sort => $operation ];
    }

    public function handlePage() {
        $request = $this->request();
        $page = intval($request->getRequestParam('page'));
        if (!$page){
            $page = 1;
        }

        return $page;
    }

   public function filter_with_relations(array $input_relations, array $legal_relations)
    {
        foreach ($input_relations as $k => $input_relation) {
            if (!in_array($input_relation, $legal_relations)) {
                unset($input_relations[$k]);
            }
        }

        return $input_relations;
    }

    public function success($data)
    {
        //这里之所以使用is_null，是避免过滤掉0、0.0这种格式的数据
        if (is_null($data)) {
            $data = '';
        }

        $this->writeJson(Status::CODE_OK , $data , '操作成功');
        return;
    }

    public function error($errcode = null, $errstr = null)
    {
        if (empty($errstr)) {
            $errstr = "未定义错误";
        }

        if (empty($errcode)) {
            $this->writeJson(Status::CODE_INTERNAL_SERVER_ERROR , $errstr , '操作失败');
            return;
        } else {
            $this->writeJson($errcode , $errstr , '操作失败');
            return;
        }
    }






}