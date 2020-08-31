<?php
namespace app\mobile\controller;

use app\common\controller\HomeBase;
use think\Db;

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
    }
    public function index()
    {
       
        return $this->fetch();
    }
    
    public function aboutUs()
         {
             return $this->fetch();
         }
    
    public function awards()
    {
        return $this->fetch();
    }
    
    public function awardsDetails()
    {
        return $this->fetch();
    }
    
    public function cityPartner()
    {
        return $this->fetch();
    }
    
    public function footerTab()
    {
        return $this->fetch();
    }
    
    public function header()
    {
        return $this->fetch();
    }
    
    public function news()
    {
        return $this->fetch();
    }

    public function newsDetails()
    {
        return $this->fetch();
    }

    public function recruitment()
    {
        return $this->fetch();
    }

    public function service()
    {
        return $this->fetch();
    }

    public function workDetails()
    {
        return $this->fetch();
    }

    public function worksdisplay()
    {
        return $this->fetch();
    }

    public function site()
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
    public function get_article_list($cid = 0){
        $list = Db::name('article')->where('cid',$cid)->order('sort DESC,id DESC')->select();
        $this->success('ok','',$list);
    }
    /**
     * 文章详情
     * @param unknown $id
     */
    public function get_article($id){
        $list = Db::name('article')->find($id);
        $list['photo'] = unserialize($list['photo']);
        $this->success('ok','',$list);
    }

    public function get_article_list_f($num=20,$id){
        
        $list = Db::name('category')->where('pid',$id)->select();
        dump($list);die;
        $list = Db::name('article')->where('cid',$cid)->order('sort DESC,id DESC')->select();
        $this->success('ok','',$list);
    }
    
    /**
     * 二级分类
     * @param unknown $id
     */
    public function get_category($id){
        $list = Db::name('category')->where('pid',$id)->select();
        $this->success('ok','',$list);
    }
    /**
     * 分类详情
     * @param unknown $id
     */
    public function get_cate($id){
        $list = Db::name('category')->where('id',$id)->find();
        $this->success('ok','',$list);
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
