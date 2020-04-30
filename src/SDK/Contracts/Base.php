<?php

namespace SDK\Contracts;

class Base{

    public $config;
    public $hw_token = '';
    public function __construct(array $options)
    {
        if (empty($options['username'])) {
            throw new \Exception("Missing Config -- [username]");
        }
        if (empty($options['password'])) {
            throw new \Exception("Missing Config -- [password]");
        }
        if (empty($options['domainName'])) {
            throw new \Exception("Missing Config -- [domainName]");
        }
        if (empty($options['regionName'])) {
            throw new \Exception("Missing Config -- [regionName]");
        }

        $this->config = $options;
    }
    public function GetToken()
    {
        $timeout=3;
        $timenow=0;
        while ($timenow<$timeout) {
            $timenow++;
            if (!empty($this->hw_token)) {
                return $this->hw_token;
            }
       
            $requestBody =  $this->RequestBodyForGetToken();
            $_url = "https://iam.myhuaweicloud.com/v3/auth/tokens" ;
            $headers = array(
                "Content-Type:application/json"
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
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($curl);

            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $headers = substr($response, 0, $headerSize);
            curl_close($curl);
            $this->hw_token = $this-> GetTokenByHeaders($headers);

            if (!empty($this->hw_token)){
                break;
            }
        }
        if (empty($this->hw_token)) {
            return null;
        }
        return $this->hw_token;
    }
    public function RequestBodyForGetToken()
    {
        $param = array(
            "auth" => array(
                "identity" => array(
                    "password" => array(
                        "user" => array(
                            "password" => $this->config['password'],
                            "domain" => array(
                                "name" => $this->config['domainName']
                            ),
                            "name" => $this->config['username']
                        )
                    ),
                    "methods" => array("password")

                ),
                "scope" => array(
                    "domain"=>array(
                        "name" =>$this->config['domainName']
                    )
                )
            )
        );
        return json_encode($param);

    }

    /* get the value of token */
    public function GetTokenByHeaders($headers)
    {
        $headArr = explode("\r\n", $headers);
        foreach ($headArr as $loop) {
            if (strpos($loop, "X-Subject-Token") !== false) {
                $token = trim(substr($loop, 17));
                return $token;
            }
        }
        return null;
    }

}