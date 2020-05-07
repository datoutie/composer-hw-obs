<?php
namespace SDK;
use \SDK\Contracts\Base;

class Api{
    public function GetSecuritytokens($hw_token,$seconds)
    {
        $asak=array();
        $timeout=3;
        $timenow=0;
        while ($timenow<$timeout) {
            $timenow++;
            $requestBody =  $this->RequestBodyForGetSecuritytokens($seconds);
            $_url = "https://iam.myhuaweicloud.com/v3.0/OS-CREDENTIAL/securitytokens" ;
            $headers = array(
                "Content-Type:application/json;charset=utf8",
                "X-Auth-Token:".$hw_token
            );

            /* 设置请求体 */
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $_url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_NOBODY, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($curl);

            curl_close($curl);
            //print_r($response);exit;
            //$response=json_decode($response,true);
            $asak = $this-> GetTokenByResponse($response);

            if (!empty($asak)){
                break;
            }
        }
        if (empty($asak)) {
            return null;
        }
        return $asak;
    }
    public function RequestBodyForGetSecuritytokens($seconds)
    {
        $param = array(
            "auth" => array(
                "identity" => array(
                    "token" => array(
                        "duration-seconds"=>$seconds
                    ),
                    "methods" => array("token"),
                    "policy" => array(
                        "Version"=>"1.1",
                        "Statement"=>array(
                            array(
                                "Effect"=>"Allow",
                                "Action"=>array(
                                    "obs:bucket:ListBucketMultipartUploads",
                                    "obs:object:PutObject",
                                    "obs:object:AbortMultipartUpload",
                                    "obs:object:ListMultipartUploadParts"
                                )
                            )
                        )
                    )
                )
            )
        );
        return json_encode($param);
    }
    public function GetTokenByResponse($response)
    {
        
        $msg=array('result'=>false,'data'=>array());
        $response=json_decode($response,true);
        if(isset($response['credential']))
        {
            $msg['result']=true;
            $msg['data']=$response['credential'];
        }
        else
        {
            //error_msg
            //error
            $msg['data']=$response;
        }
        return $msg;
        
    }
    public function GetNotify()
    {
        $result=array('result'=>false,'data'=>'');
        $data=file_get_contents('php://input');
        $data=json_decode($data,true);
        if(isset($data['subscribe_url']))
        {
            $result['data']=$data['subscribe_url'];
        }
        else
        {
            $data['message']=json_decode($data['message'],true);
            $result['result']=true;
            $result['data']=$data['message'];
        }
        return $result;
    }


    public function GetAssetId($hw_token,$project_id,$region_name,$title,$video_name,$video_type,$workflow_name)
    {
        $asak=array();
        $timeout=3;
        $timenow=0;
        while ($timenow<$timeout) {
            $timenow++;
            $requestBody =  $this->RequestBodyForGetGetAssetId($title,$video_name,$video_type,$workflow_name);
            $_url = "https://vod.".$region_name.".myhuaweicloud.com/v1.0/".$project_id."/asset" ;
            $headers = array(
                "Content-Type:application/json;charset=utf8",
                "X-Auth-Token:".$hw_token
            );

            /* 设置请求体 */
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $_url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_NOBODY, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($curl);

            curl_close($curl);
            //print_r($response);exit;
            //$response=json_decode($response,true);
            $asak = $this-> GetAssetIdByResponse($response);

            if (!empty($asak)){
                break;
            }
        }
        if (empty($asak)) {
            return null;
        }
        return $asak;
    }
    public function RequestBodyForGetGetAssetId($title,$video_name,$video_type,$workflow_name)
    {
        $param = array(
            "title" =>$title,
            "video_name"=>$video_name,
            "video_type"=>$video_type,
            "auto_publish"=>1,
            "workflow_name"=>$workflow_name
        );
        return json_encode($param);
    }
    public function GetAssetIdByResponse($response)
    {
        
        $msg=array('result'=>false,'data'=>array());
        $response=json_decode($response,true);
        if(isset($response['asset_id']))
        {
            $msg['result']=true;
            $msg['data']=$response;
        }
        else
        {
            $msg['data']=$response;
        }
        return $msg;
        
    }


    public function GetAssetUploaded($hw_token,$region_name,$asset_id,$project_id)
    {
        $asak=array();
        $timeout=3;
        $timenow=0;
        while ($timenow<$timeout) {
            $timenow++;
            $requestBody =  $this->RequestBodyForGetGetAssetUploaded($asset_id);
            $_url = "https://vod.".$region_name.".myhuaweicloud.com/v1.0/".$project_id."/asset/status/uploaded" ;
            $headers = array(
                "Content-Type:application/json;charset=utf8",
                "X-Auth-Token:".$hw_token
            );

            /* 设置请求体 */
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $_url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_NOBODY, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($curl);

            curl_close($curl);
            //print_r($response);exit;
            //$response=json_decode($response,true);
            $asak = $this-> GetAssetUploadedByResponse($response);

            if (!empty($asak)){
                break;
            }
        }
        if (empty($asak)) {
            return null;
        }
        return $asak;
    }
    public function RequestBodyForGetGetAssetUploaded($asset_id)
    {
        $param = array(
            "asset_id" =>$asset_id,
            "status"=>"CREATED"
        );
        return json_encode($param);
    }
    public function GetAssetUploadedByResponse($response)
    {
        
        $msg=array('result'=>false,'data'=>array());
        $response=json_decode($response,true);
        if(isset($response['asset_id']))
        {
            $msg['result']=true;
            $msg['data']=$response;
        }
        else
        {
            $msg['data']=$response;
        }
        return $msg;
        
    }
}