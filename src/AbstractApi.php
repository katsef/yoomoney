<?php
namespace Webazon\Yoomoney;function isJSON($string){return((is_string($string)&&(is_object(json_decode($string))||is_array(json_decode($string)))))?true:false;}
class Config{static $YOOMONEY_URL="https://yoomoney.ru";}
abstract class AbstractApi{protected function AuthSend(){$res=new\stdClass();$res->status=false;if(!isset($this->ClientId)){$res->error='client_id_not_found';$res->error_description='Неверные входные параметры';return $res;}
if(!isset($this->RedirectUri)){$res->error='redirect_uri_not_found';$res->error_description='Неверные входные параметры';return $res;}
if(!isset($this->Scope)){$res->error='scope_not_found';$res->error_description='Неверные входные параметры';return $res;}
$options=array('client_id'=>$this->ClientId,'response_type'=>'code','redirect_uri'=>$this->RedirectUri,'scope'=>$this->Scope);if(isset($this->InstaceName)){$options['instance_name']=$this->InstaceName;}
$query=http_build_query($options);$length=strlen($query);$curl=curl_init('https://yoomoney.ru/oauth/authorize');curl_setopt($curl,CURLOPT_USERAGENT,'Yoomoney.SDK/PHP');curl_setopt($curl,CURLOPT_POST,1);curl_setopt($curl,CURLOPT_POSTFIELDS,$query);curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);curl_setopt($curl,CURLOPT_HEADER,1);curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,true);curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);$result=curl_exec($curl);$out=preg_split('/(\r?\n){2}/',$result,2);$headers=$out[0];$headersArray=preg_split('/\r?\n/',$headers);$headersArray=array_map(function($h){return preg_split('/:\s{1,}/',$h,2);},$headersArray);$tmp=[];foreach($headersArray as $h){$pos=strpos($h[0],'HTTP');if($pos===false){$tmp[strtolower($h[0])]=isset($h[1])?$h[1]:$h[0];}else{$tmp['http']=isset($h[1])?$h[1]:$h[0];}}
$headersArray=$tmp;$tmp=null;if($headersArray['http']=='HTTP/1.1 302 Found'&&isset($headersArray['location'])&&isset($headersArray['set-cookie'])){$cook=array();foreach(explode('; ',$headersArray['set-cookie'])as $k=>$v){preg_match('/^(.*?)=(.*?)$/i',trim($v),$matches);array_push($cook,array(trim($matches[1]),urldecode($matches[2])));}
$cookies=new\stdClass();$cookies->name=$cook[0][0];$cookies->value=$cook[0][1];$cookies->path=$cook[1][1];$result=new\stdClass();$result->auth_url=$headersArray['location'];$result->cookies=$cookies;$res->status=true;$res->result=$result;}else{$res->error='error';}
return $res;}
protected function sendRequest($url='',$options=array()){if(strpos($url,"https")===false){$full_url=Config::$YOOMONEY_URL.$url;}
else{$full_url=$url;}
$curl=curl_init($full_url);if($this->AccessToken!==NULL){curl_setopt($curl,CURLOPT_HTTPHEADER,array("Authorization: Bearer ".$this->AccessToken));}
curl_setopt($curl,CURLOPT_USERAGENT,'Yoomoney.SDK/PHP');curl_setopt($curl,CURLOPT_POST,1);$query=http_build_query($options);curl_setopt($curl,CURLOPT_POSTFIELDS,$query);curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);curl_setopt($curl,CURLOPT_HEADER,1);curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,true);curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);$body=curl_exec($curl);$out=preg_split('/(\r?\n){2}/',$body,2);$headers=$out[0];$headersArray=preg_split('/\r?\n/',$headers);$headersArray=array_map(function($h){return preg_split('/:\s{1,}/',$h,2);},$headersArray);$tmp=[];foreach($headersArray as $h){$pos=strpos($h[0],'HTTP');if($pos===false){$tmp[strtolower($h[0])]=isset($h[1])?$h[1]:$h[0];}else{$tmp['http']=isset($h[1])?$h[1]:$h[0];}}
$headersArray=$tmp;$tmp=null;$result_code=0;$http_text=false;if(isset($headersArray['http'])){$a=explode(' ',$headersArray['http'],3);if(isset($a[1])){$result_code=intval($a[1]);}
if(isset($a[2])){$http_text=$a[2];}}
$error=false;$error_description=false;$status=false;if(isset($headersArray['www-authenticate'])){$str=$headersArray['www-authenticate'];$str=str_replace('"','',$str);$a=explode(' ',$str,2);if($a[0]=='Bearer'&&isset($a[1])){$x=explode(',',$a[1]);$a=array();for($i=0;$i<count($x);$i++){$y=explode('=',$x[$i],2);$a[trim($y[0])]=$y[1];}
if(isset($a['error'])){$error=$a['error'];}
if(isset($a['error_description'])){$error_description=$a['error_description'];}}}
else{if($result_code!==200){if($http_text){$error=$http_text;}
if($result_code==404){$error='unsupported_method';}
if($result_code==500){$error='internal_server_error';}}}
$h=$out[0];$b=$out[1];$result=new\StdClass();$result->status=$status;$result->result_code=$result_code;if($error){$error=strtolower($error);$error=str_replace(' ','_',$error);$result->error=$error;if($error_description){$result->error_description=$error_description;}}
if(isJSON($b)&&$result_code==200){$b=json_decode($b,true);if(isset($b['error'])){$result->status=false;$result->error=$b['error'];}
else{$result->status=true;$result->response=json_decode(json_encode($b));}}
else{if($result_code==200){$result->status=true;$result->response=new\StdClass();;}}
curl_close($curl);return $result;}}