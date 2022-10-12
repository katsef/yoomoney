<?php
namespace Webazon\Yoomoney;class Auth extends AbstractApi{public function __construct($options=[]){if(isset($options['client_id'])){$this->ClientId=$options['client_id'];}
if(isset($options['client_secret'])){$this->ClientSecret=$options['client_secret'];}
if(isset($options['redirect_uri'])){$this->RedirectUri=$options['redirect_uri'];}
if(isset($options['scope'])){$this->Scope=$options['scope'];}
if(isset($options['instance_name'])){$this->InstaceName=$options['instance_name'];}}
public function getAuthUrl(){return $this->AuthSend();}
public function getAccessToken($code=NULL){$options=array('code'=>$code,'client_id'=>$this->ClientId,'grant_type'=>'authorization_code','redirect_uri'=>$this->RedirectUri);$res=$this->sendRequest('/oauth/token',$options);if(isset($res->response->access_token)){if(strlen($res->response->access_token)==273){}
else{$res->status=false;$res->error='empty_token';unset($res->response);unset($res->result_code);}}
return $res;}
public function getClientId(){return $this->ClientId;}}?>