<?php
namespace app\admin\controller;

use app\common\model\Message as MessageModel;
use app\common\controller\AdminBase;
use think\Config;
use think\Db;

/**
 * 用户管理
 * Class AdminUser
 * @package app\admin\controller
 */
class Message extends AdminBase
{
    protected $message_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->message_model = new MessageModel();
    }

    /**
     * 用户管理
     * @param string $keyword
     * @param int    $page
     * @return mixed
     */
    public function index($keyword = '', $page = 1)
    {
        $map = [];
        if ($keyword) {
            $map['company|mobile|full_name'] = ['like', "%{$keyword}%"];
        }
        $user_list = $this->message_model->where($map)->order('id DESC')->paginate(15, false, ['page' => $page]);

        return $this->fetch('index', ['user_list' => $user_list, 'keyword' => $keyword]);
    }

    /**
     * 添加用户
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 保存用户
     */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Message');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $data['password'] = md5($data['password'] . Config::get('salt'));
                if ($this->message_model->allowField(true)->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
     * 编辑用户
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $user = $this->message_model->find($id);

        return $this->fetch('edit', ['user' => $user]);
    }

    /**
     * 更新用户
     * @param $id
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Message');
            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $user           = $this->message_model->find($id);
                $user->id       = $id;
                $user->full_name = $data['full_name'];
                $user->mobile   = $data['mobile'];
                $user->company    = $data['company'];
                $user->status   = $data['status'];
                if ($user->save() !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
     * 删除用户
     * @param $id
     */
    public function delete($id)
    {
        if ($this->message_model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    
}