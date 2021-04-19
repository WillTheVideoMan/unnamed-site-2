<?php
class GoogleLoginHelper
{
	public function GetAuthURL($clientID, $redirectURL, $scopes = array())
	{
		$url = 'https://accounts.google.com/o/oauth2/v2/auth?';
		
		$scopeList = "";
		
		foreach($scopes as $scope) $scopeList .= $scope . " ";
		
		$url .= 'scope=' . urlencode($scopeList);
		
		$url .= '&redirect_uri=' . urlencode($redirectURL);
		
		$url .= '&client_id=' . $clientID;
		
		$url .= '&access_type=online&response_type=code&hd=ashville.co.uk&prompt=select_account';
		
		return $url;
	}
	
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
		$url = 'https://accounts.google.com/o/oauth2/token';			
		
		$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to receieve access token');
			
		return $data;
	}

	public function GetUserProfileInfo($access_token) {	
		
		return file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token);
	}
}
?>