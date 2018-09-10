<?php
namespace controllers;

use Yansongda\Pay\Pay;

class AlipayController
{
    public $config = [
        'app_id' => '2016091700530951',
        // 通知地址
        'notify_url' => 'http://requestbin.fullcontact.com/y106aty1',
        // 跳回地址
        'return_url' => 'http://localhost:9999/alipay/return',
        // 支付宝公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArYIkcmyCY7KoOjAY51ffLEKA4h11zezILxqq/e0jGGMNt+skyhSEb7ic2wJgNiQIVAakFsNymr404+JpiiCrBP+mAiJzErSIaKkpS/2YGem5xrg26RywVFaeDMLnKDAVYa2veaWxKDbypNffryVy4Vz15tYClrWKkobyWlFXVCrPTy4Csovo1syz7/r6Zz+78lsrioXJazR3bEPV7EEMDWOVN9YIVvN/mIOp0aedaE3BNAg4kx+GEgFt0zLGiqbEyFL/EWr5g++yAegq4yGeuFDzHilxiE2w6/7yvm1BV+UGjpQ1kkIojajuFjv7hZeRy4RjrYZObzkz3Ef4d5c7DQIDAQAB',
        // 商户应用密钥
        'private_key' => 'MIIEowIBAAKCAQEA0BPkYaU4+alMsoCcPqzF3dYygbFTGdr866CePapmTOnuWYPWGDqgtYwqiAxRyVfMrUffKFh/3KxZpnsLKwlyk4SxSnvuZEBUQ0n4bAgXWjXaV7pSaDYUoj+qaUZ5iWVh1vqNpOh1QXE2ZhsQrKZC8EqXeNWIHHSMlJESZI8y0fsT+pZbb0tGKSpaOimR4fbM+aV3uOmPLYhHI6ft4G745nwCZ4RV0frOJs82/SHht3gH8QTH3f/c3u6t+4MfcjHsfPb2M3ggRW4lH6yOKVrcjjjKgFBGNfh7M4bam+JXi4C1jTs5SFanUPmg/ysZy9y31Pgn+5hweBmgOsR5qzcl2QIDAQABAoIBAG3HEOazVKvjNiO21rf6TyoKp/rkowMxFd76joHaNL6/bJCtjXaa987QdF/yn/Op7sI8+O9AQbUa+PFsWSgLg/dwOlkhqVQa3Fnj3LyiSC+/2cIO+wsf/SLck/uWwuhKJKRW7APk4Hf2Uszr2pA753T+5YtKnNTmaIS5RQrowdclvItkwECrYfLPI0j5ZLD0bpwI0A5xkmFrG0y3sc1xHFFQKHoqwAqy19mF2G7soIcweFftOVRj6ykqa8N32OLZsGnTEot8c/QeaUmkW/I93eejnYrVGHwa5bUoIX84liD9CUtSeQtY8Ukk62IK/jx7n+bTKFH79TgBHUej9y6puAECgYEA6AITigqZSVWCT2qzcfyfWK7Ul0lh2oTrQPW/skA0aUGcBkcViXRgVxjhfS+CHbL3nHhBE7P1o0033+E5b7oF3W/kOdlOKST2XLVpnc6JP+2vMkBP/mWIYVbhVHfDUkNab7CNZfucjztFvtNM7lD4rdiu7cqn4eEVUbBy3JjiQLECgYEA5ZhPFOCj/VBj/7uz3IgpYAgQ4ENzFkAloxX0kGhpKd2lJ4bC80zCmvnOjixbiOA+jrY0tXSS72652UD/rHETKEIAKfEYFNti1YFCx2+YguE1F33R0XXJw70IZFMTm8c80sWcJFhSWEbRs+tCCFwEaAcLKJ7XoFSDBhgcUEmlwakCgYAN7xH9WGWcYeZRnrboQaPQT6+05lNaLGF0pU2+Bt0e9THJrrs8ZiDjGjtwqUnHPthgLVi7NjOqexi/+WbZrvJrOi0azFJFZlLlfn+5Wo4jqMK84d/sB20Ja45c5FR2vFTSIGhdl57vez/VFhuotHQ6/KiE34b1qe792PsNBKGSAQKBgHo7s2aPDl+PjB2bhe9UAosg4DM1VbEz55XC25iCLfLfEM1RXrr/U+AEPObOUNE3aba65KbycEPOtF0o0LWy4ZseQE4UEFkST7URg0cAb7bGRWjDUJBuYEO4gR5AaIEX+pbQJAfpibV7xqs+BcLDkoj7rwIqRuwR8kutTG2mN/w5AoGBAKBWDQ5xB/lsEjeCTe81NIUGkY12A8Kprn+RYE+Tzvi0dyHZoLlOah60ebLZL5EbviP0zjH9WzHa/hN+Tq/P0cntyZcDvqQRzzn7+jLGGgT+BOThndesJWGENrFvpOV+4JkjWInwW3C66rRTh5fypngD2qRx+/khGou0PybmsAQR',
        // 沙箱模式（可选）
        'mode' => 'dev',
    ];
    // 发起支付
    public function pay()
    {
        $order = [
            'out_trade_no' => time(),    // 本地订单ID
            'total_amount' => '648000',    // 支付金额
            'subject' => 'test subject', // 支付标题
        ];

        $alipay = Pay::alipay($this->config)->web($order);

        $alipay->send();
    }
    // 支付完成跳回
    public function return()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！
        echo '<h1>支付成功！</h1> <hr>';
        echo "<pre>";
        var_dump( $data->all() );
    }
    // 接收支付完成的通知
    public function notify()
    {
        $alipay = Pay::alipay($this->config);
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 这里需要对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            echo '订单ID：'.$data->out_trade_no ."\r\n";
            echo '支付总金额：'.$data->total_amount ."\r\n";
            echo '支付状态：'.$data->trade_status ."\r\n";
            echo '商户ID：'.$data->seller_id ."\r\n";
            echo 'app_id：'.$data->app_id ."\r\n";
        } catch (\Exception $e) {
            echo '失败：';
            var_dump($e->getMessage()) ;
        }
        // 返回响应
        $alipay->success()->send();
    }
}