<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-11-30
 * Time: 上午11:07
 */

namespace App\HttpController\Modules\Customer\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

class Customer extends AbstractModel
{
    protected $tableName = 'customer';

    public function getFieldData()
    {
        $params = [
                    'customer.id',
                    'customer.is_shop',
                    'customer.is_direct_promotion',
                    'customer.is_fission',
                    'customer.created_at',
                    'customer.updated_at'
                   ];
        return implode(',',$params);
    }



}