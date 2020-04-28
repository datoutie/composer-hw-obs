<?php
require '../vendor/autoload.php';
use \SDK\Api;
use \SDK\Contracts\Base;

try {
    //第一步
    $Base=new Base(array('username'=>'','password'=>'','domainName'=>'','regionName'=>'cn-north-4'));
    $token=$Base->GetToken();//缓存一下
    //第二步
    $Api=new Api();
    $aksk=$Api->GetSecuritytokens($token,3600,'cn-north-4');
    if($aksk['result'])
    {
        print_r($aksk['data']);
        //['access']
        //['secret']
        //['securitytoken']
    }
    else
    {
        //error 重新GetToken
    }

//第三步
    $notify=$Api->GetNotify();//获取转码后通知
    if($notify['result'])
    {
        $notifydata=$notify['data'];
        echo $notifydata['transcode_info'];
        //['asset_id'];
        //['title']
        //['status']=SUCCEED
        //['output']=(array)
        //[0]['quality']=FLUENT
        //[0]['url']=''

    }

} catch (\Exception $th) {
   echo $th->getMessage();
}