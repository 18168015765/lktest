<?php
namespace app\common\model;

use think\Model;

class Message extends Model
{
    protected $insert = ['time'];

    /**
     * 创建时间
     * @return bool|string
     */
    protected function setCreateTimeAttr()
    {
        return date('Y-m-d H:i:s');
    }
}