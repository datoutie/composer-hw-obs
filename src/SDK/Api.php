<?php
namespace SDK;
use \SDK\Contracts\Base;

class Api{
    public function GetSecuritytokens($hw_token,$seconds,$regionName)
    {
        $asak=array();
        $timeout=3;
        $timenow=0;
        while ($timenow<$timeout) {
            $timenow++;
            $requestBody =  $this->RequestBodyForGetSecuritytokens($hw_token,$seconds);
            $_url = "https://iam.".$regionName.".myhuaweicloud.com/v3.0/OS-CREDENTIAL/securitytokens" ;
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
    public function RequestBodyForGetSecuritytokens($hw_token,$seconds)
    {
        $param = array(
            "auth" => array(
                "identity" => array(
                    "token" => array(
                        "id"=>$hw_token,
                        "duration-seconds"=>$seconds
                    ),
                    "methods" => array("token")

                ),
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
        );
        return json_encode($param);
    }
    public function GetTokenByResponse($response)
    {
        
        $msg=array('result'=>'false','data'=>array());
        $response=json_decode($response,true);
        if(isset($response['error_msg']))
        {
            $msg['data']=$response;
            return $msg;
        }
        else
        {
            if(isset($response['credential']))
            {
                $msg['result']=true;
                $msg['data']=$response;
                return $msg;
            }
            else
            {
                return null;
            }
        }
        
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
            $result['result']=true;
            $result['data']=$data['message'];
        }
        return $result;
    }
}