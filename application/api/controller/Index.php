<?php
namespace app\api\controller;

use app\common\controller\HomeBase;
use think\Db;
use app\common\model\Article as ArticleModel;
use app\common\model\Category as CategoryModel;
use app\common\controller\AdminBase;


class Index extends HomeBase
{

    protected function _initialize()
    {
        /*parent::_initialize();
        $arr = array('get_banner','get_article_list','get_article','get_category','get_cate','search');
        if(!in_array($this->request->action(), $arr) ){


            if(is_mobile() && $this->request->module() !='mobile'){
                $this->redirect('mobile/index/index');
            }elseif (!is_mobile() && $this->request->module() !='index')  {
                $this->redirect('index/index/index');
            }
        }*/
        parent::_initialize();
        $this->category_model = new CategoryModel();
        $this->article_model  = new ArticleModel();

    }
    public function index()
    {
        $banner = Db::name('slide')->where('cid',1)->select();
        return $this->fetch('',['banner'=>$banner]);
    }

    public function bzx()
    {
        return $this->fetch();
    }

    public function cchl()
    {
        return $this->fetch();
    }

    public function mbhl()
    {
        return $this->fetch();
    }

    public function footer()
    {
        return $this->fetch();
    }

    public function header()
    {
        return $this->fetch();
    }

    public function sdxjtlx()
    {
        return $this->fetch();
    }
    public function article()
    {
        return $this->fetch();
    }
    public function xlbbyzx()
    {
        return $this->fetch();
    }
    public function yyjs()
    {
        return $this->fetch();
    }
    public function zxrj()
    {
        return $this->fetch();
    }
    public function lylx()
    {
        return $this->fetch();
    }
    public function tuomao()
    {
        return $this->fetch();
    }
    public function zxrjlb()
    {
        return $this->fetch();
    }
    public function zxrjmb()
    {
        return $this->fetch();
    }
    public function zxrjzh()
    {
        return $this->fetch();
    }

    /**
     * banner图
     * @param number $cid
     */
    public function get_banner($cid = 0){
        $banner = Db::name('slide')->where('cid',$cid)->select();
        $this->success('ok','',$banner);
    }
    /**
     * 文章列表
     * @param number $cid
     */
    public function get_article_list(){
        $data = array(
                'status'    =>  500,
                'message'   =>  '接口调用失败',
                'data'  =>  []
            );
        $cid = input('get.cid') ? input('get.cid') : 0;
        $page = input('get.page') ? input('get.page') : 0;
        $size = input('get.size') ? input('get.size') : 10;
        $list = Db::name('article')->where('cid',$cid)->order('sort DESC,id DESC')->limit($page,$size)->select();
        if(!empty($list)){
            $data = array(
                'status'    =>  200,
                'message'   =>  '查询成功',
                'data'  =>  $list
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 文章详情
     * @param unknown $id
     */
    public function get_article(){
        $data = array(
                'status'    =>  500,
                'message'   =>  '接口调用失败',
                'data'  =>  []
            );

        $id = input('get.id');
        if(!$id){
            $data['message'] = "缺少文章id";
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        $list = Db::name('article')->find($id);
        $list['photo'] = unserialize($list['photo']);
        if(!empty($list)){
            $data = array(
                'status'    =>  200,
                'message'   =>  '查询成功',
                'data'  =>  $list
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    public function get_article_list_f($num=20,$id){

        $list = Db::name('category')->where('pid',$id)->select();
        dump($list);die;
        $list = Db::name('article')->where('cid',$cid)->order('sort DESC,id DESC')->select();
        $this->success('ok','',$list);
    }

    /**
     * 获取一级分类
     */
    public function get_all_category(){
        $data = array(
                'status'    =>  500,
                'message'   =>  '接口调用失败',
                'data'  =>  []
            );

        $category_level = Db::name('category')->order(['sort' => 'DESC'])->select();
        $list = array2tree($category_level);
        if(!empty($list)){
            $data = array(
                'status'    =>  200,
                'message'   =>  '查询成功',
                'data'  =>  $list
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 二级分类
     * @param unknown $id
     */
    public function get_category($id){
        $list = Db::name('category')->where('pid',$id)->select();
        dump($list);die;
        $this->success('ok','',$list);
    }
    /**
     * 分类详情
     * @param unknown $id
     */
    public function get_cate($id){
        $data = array(
                'status'    =>  500,
                'message'   =>  '接口调用失败',
                'data'  =>  []
            );
        $list = Db::name('category')->where('id',$id)->find();
        if(!empty($list)){
            $data = array(
                'status'    =>  200,
                'message'   =>  '查询成功',
                'data'  =>  $list
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 文章关键字搜索
     * @param unknown $search
     */
    public function search($search){
        //
        $map = array();
        $map['title|introduction'] = array('like','%'.$search.'%');
        $list = Db::name('article')->where($map)->select();
        $this->success('ok','',$list);
    }


    /**
     * 留言（只有手机号）
     * @param number $mobile
     */
    public function set_mobile($mobile = 0){
        if (check_mobile_number($mobile)){
            $map = array('mobile'=>$mobile);
            $info = Db::name('Message')->where($map)->find();
            if ($info){
                return $this->error('该手机号已预留');
            }
            $data = array();
            $data['company'] = '';
            $data['full_name'] = '';
            $data['mobile'] = $mobile;
            $data['province'] = '';
            $data['city'] = '';
            $data['region'] = '';
            $data['status'] = 0;
            $data['time'] = date('Y-m-d H:i:s');
            $ret = Db::name('Message')->insert($data);
            if($ret){
                return $this->success('稍后客服人员会联系您，请耐心等待！');
            }
        }else{
            return $this->error('手机号不正确');
        }
    }
    /**
     * 留言（全）
     * @param number $mobile
     */
    public function set_info($company = 0,$full_name = 0,$mobile = 0,$province = 0,$city = 0,$region = 0){
        if (check_mobile_number($mobile)){
            $map = array('mobile'=>$mobile);
            $info = Db::name('Message')->where($map)->find();
            $data = array();
            $data['company'] = $company;
            $data['full_name'] = $full_name;
            $data['mobile'] = $mobile;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['region'] = $region;
            $data['status'] = 0;
            $data['time'] = date('Y-m-d H:i:s');
            /* if ($info){
                $ret = Db::name('Message')->where($map)->update($data);
                if ($ret!==false){
                    $ret = 1;
                }
            }else { */
                $ret = Db::name('Message')->insert($data);
            //}
            if($ret){
                return $this->success('稍后客服人员会联系您，请耐心等待！');
            }
        }else{
            return $this->error('手机号不正确');
        }
    }
}
