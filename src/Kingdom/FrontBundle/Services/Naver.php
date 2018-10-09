<?php

namespace Kingdom\FrontBundle\Services;

use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

/**
 * Description of Naver
 *
 * @author kerius
 */
class Naver {
    private static $config = array(
        "client" => "25VUaXJpKFi9qaKzPqIg",
        "secret" => "WV3nuRajrY"
    );
    
    public function getConfig($key){
        return self::$config[$key];
    }
            
    public function generateState(){
     $mt = microtime();
     $rand = mt_rand();     
     return md5($mt . $rand);
    } 
 
    //ログイン後処理
    public function loginProcess($controller, $code, $state){      
        $userData = array();                
        $tokenUrl = "https://nid.naver.com/oauth2.0/token";              
        $tokenParam = array(    
            "client_id" => self::$config["client"],
            "client_secret" =>self::$config["secret"],
            "grant_type" => "authorization_code",
            "state" => $state,
            "code" => $code,
                      );                
        $result = $controller->get("api_caller")->call(new HttpGetJson($tokenUrl, $tokenParam));                                
       
        
        
        if(isset($result->error)){
         echo "Login Error";
        }else{
         //ユーザー情報
            $userUrl = "https://apis.naver.com/nidlogin/nid/getUserProfile.xml";            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $userUrl); // URLをセット
            curl_setopt($curl, CURLOPT_HEADER, "Content-Type:application/xml"); 
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization : Bearer ".$result->access_token));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列で返す            
            $data =curl_exec($curl);
            curl_close($curl);
            $xmlData = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);            
            if($xmlData->result->resultcode !="00"){
                  return 0;
             }else{ 
                $userData = array (
                    "enc_id" => (string)$xmlData->response->enc_id,
                    "email" => (string)$xmlData->response->email,
                    "nickname" => (string)$xmlData->response->nickname,
                    "profile_image" => (string)$xmlData->response->profile_image,
                    "age" => (string)$xmlData->response->age,
                    "birthday" => (string)$xmlData->response->birthday,
                    "gender" => (string)$xmlData->response->gender,
                );
            }
            
        }
        
        return $userData;
    }
}
