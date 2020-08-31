<?php
namespace app\index\controller;

use app\common\controller\HomeBase;
use think\Db;

//微信支付开始
use Wxpay\WxPayUnifiedOrder;
use Wxpay\WxPayConfig;
//微信支付结束

class Test extends HomeBase
{
    
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        header("Content-type:text/html;charset=utf-8");
 
        // require '../../../extend/Wxpay/WxPay.Api.php'; //引入微信支付
        
        $config = new WxPayConfig();//配置参数
        $input = new WxPayUnifiedOrder();//统一下单
        //$paymoney = input('post.paymoney'); //支付金额
        $paymoney = 1; //测试写死
        $out_trade_no = 'WXPAY'.date("YmdHis"); //商户订单号(自定义)
        $goods_name = '扫码支付'.$paymoney.'元'; //商品名称(自定义)
        $input->SetBody($goods_name);
        $input->SetAttach($goods_name);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($paymoney*100);//金额乘以100
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://www.ttt.com/index/test/wxpaynotify"); //回调地址
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");//商品id
        $result = \WxPayApi::unifiedOrder($config, $input);
 
        if($result['result_code']=='SUCCESS' && $result['return_code']=='SUCCESS') {
            $url = $result["code_url"];
            $this->assign('url',$url);
        }else{
            $this->error('参数错误'); 
        }
        return $this->fetch();
    }

    public function wxpaynotify() {
        // 获取微信回调的数据
        $notifiedData = file_get_contents('php://input');
     
        //XML格式转换
        $xmlObj = simplexml_load_string($notifiedData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $xmlObj = json_decode(json_encode($xmlObj), true);
     
        //支付成功
        if ($xmlObj['return_code'] == "SUCCESS" && $xmlObj['result_code'] == "SUCCESS") {
            foreach ($xmlObj as $k => $v) {
                if ($k == 'sign') {
                    $xmlSign = $xmlObj[$k];
                    unset($xmlObj[$k]);
                };
            }
            $sign = $this->WxSign($xmlObj);
            if ($sign === $xmlSign) {
                $trade_no = $xmlObj['out_trade_no']; //商户自定义订单号
                $transaction_id = $xmlObj['transaction_id']; //微信交易单号
     
                //省略订单处理逻辑...
     
                //返回成功标识给微信
                return sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
            }
     
        }
    }
     
    //微信签名算法
    private function WxSign($param)
    {
        $signkey = 'xxx';//秘钥
        $sign = '';
        foreach ($param as $key => $val) {
            $sign .= $key . '=' . $val . '&';
        }
        $sign .= 'key=' . $signkey;
        $sign = strtoupper(MD5($sign));
        return $sign;
    }

}
