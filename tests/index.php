<?php
require '../vendor/autoload.php';
use \SDK\Api;
use \SDK\Contracts\Base;

try {
//第一步
    //https://developer.huaweicloud.com/endpoint?OBS
    //cn-east-2上海二
    $Base=new Base(array('username'=>'','password'=>'','domainName'=>'','regionName'=>'cn-east-2'));
    $token=$Base->GetToken();//缓存一下
//第二步
    $Api=new Api();
   
    $aksk=$Api->GetSecuritytokens($token,900);//最小900s 86400
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
//获取AssetId
    $ass=$Api->GetAssetId($token,$project_id,'cn-east-2',$title,$video_name,$video_type,$workflow_name);
    if($ass['result'])
    {
        print_r($ass['data']);
    }
//     Array
// (
//     [asset_id] => XXXX
//     [target] => Array
//         (
//             [bucket] => vod-bucket-XX-cn-east-2
//             [location] => cn-east-2
//             [object] => XX/XX/XX.mp4
//         )

// )

//确认上传
    $ok=$Api->GetAssetUploaded($token,'cn-east-2',$asset_id,$project_id);
    if($ok['result'])
    {
        print_r($ok['data']);
    }
//第三步
    // $notify=$Api->GetNotify();//获取转码后通知
    // if($notify['result'])
    // {
    //     $notifydata=$notify['data'];
    //     echo $notifydata['transcode_info'];
        // {
        //     "event_type": "transcodeComplete",
        //     "transcode_info": {
        //         "title": "14_1588134652.mp4",
        //         "status": "SUCCEED",
        //         "template_group_name": "new",
        //         "output": [{
        //             "play_type": "MP4",
        //             "url": "https://xxxx.com/655c39dabf63479aa544fc3c38a56cce.mp4",
        //             "encrypted": 0,
        //             "quality": "FLUENT",
        //             "meta_data": {
        //                 "play_type": 0,
        //                 "codec": "H.264",
        //                 "duration": 93,
        //                 "video_size": 5485568,
        //                 "width": 480,
        //                 "hight": 270,
        //                 "bit_rate": 399,
        //                 "frame_rate": 24,
        //                 "quality": "FLUENT",
        //                 "audio_channels": 0
        //             }
        //         }],
        //         "asset_id": "ee12df9b5d3a1XXXX"
        //     }
        // }

    //}

} catch (\Exception $th) {
   echo $th->getMessage();
}