<?php
namespace Webazon\Yoomoney;class Api extends AbstractApi{function __construct($access_token){$this->AccessToken=$access_token;}
public function api($name,$options=array()){return $this->sendRequest('/api/'.$name,$options);}
public function accountInfo(){return $this->sendRequest('/api/account-info');}}?>