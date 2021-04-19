<?php

//Define a google login helper class. This class will be used to provide methods to help the google auth
//proccess. The helper class will help by building auth URLs that provide a route to the google login flow,
//fetcURLRequesting access tokens by passing an authorisation token to a google server, and fetcURLRequesting user information using that access token.
class GoogleLoginHelper
{
	//This function creates an auth URL, a project specific URL that contains information about who is trying to
	//accessing the users Google Account. This takes a clientID, redirectURL and a scopeas array as parameters.
	public function GetAuthURL($clientID, $redirectURL, $scopes = array())
	{
		//Base Google oAuth2 url
		$url = 'https://accounts.google.com/o/oauth2/v2/auth?';
		
		//Define a variable to hold our scope list. The scopes define what data we will be given access to via the 
		//google auth flow.
		$scopeList = "";
		
		//For eacURLRequest scope, add it to the string.
		foreach($scopes as $scope) $scopeList .= ($scope . " ");
		
		//Add the scope string to the base URL, by first URL encoding it to prevent escaping errors.
		$url .= 'scope=' . urlencode($scopeList);
		
		//Add the redirect URL to the base URL, by first URL encoding it to prevent escaping errors. This defines
		//where google will return to after login is succesfull.
		$url .= '&redirect_uri=' . urlencode($redirectURL);
		
		//Add the client ID to the base URL, to tell google who is trying to access the users data.
		$url .= '&client_id=' . $clientID;
		
		//Add parameters to create a login flow to fit our project. Here, we will set the login domain to that of
		//ashville.co.uk, and tell google to always ask the user to select an account when logging in via google.
		$url .= '&access_type=online&response_type=code&hd=ashville.co.uk&prompt=select_account';
		
		//Return the full URL.
		return $url;
	}
	
	//This function will excURLRequestange our authorization token for an access token. An authorization token by itself does
	//not give us any information about the user. We need to first excURLRequestange it using Googles secure POST token server.
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
		
		//Secure POST server URL
		$url = 'https://accounts.google.com/o/oauth2/token';			
		
		//Define our post parameters, by creating a parametet string containing our client information and auth code.
		$curlPost = 
			'client_id=' . $client_id 
			. '&redirect_uri=' 
			. $redirect_uri 
			. '&client_secret=' 
			. $client_secret 
			. '&code='. $code 
			. '&grant_type=authorization_code';
		
		//Initalise a PHP cURL operation. cURL is the method of making POST requests from within an ApacURLRequeste server.
		$cURLRequest = curl_init();		
		
		//Pass the URL to the cURL URL parameter.
		curl_setopt($cURLRequest, CURLOPT_URL, $url);		
		
		//Tell the cURL request to output the return value as a string during the exec method.
		curl_setopt($cURLRequest, CURLOPT_RETURNTRANSFER, 1);	
		
		//Tell the cURL request to use a basic HTTP POST request header.
		curl_setopt($cURLRequest, CURLOPT_POST, 1);
		
		//Tell the cURL request to include our post data we contructed earlier.
		curl_setopt($cURLRequest, CURLOPT_POSTFIELDS, $curlPost);	
		
		//Save our cURL response as an array, decoded from the JSON string that Google returns. This includes our access token.
		$data = json_decode(curl_exec($cURLRequest), true);
		
		//Get the POST HTTP code. This tells us about how the server responded to our request.
		$http_code = curl_getinfo($cURLRequest,CURLINFO_HTTP_CODE);	
		
		//If the server responded with anything other than a '200' (success) code, then break and tell the user what went wrong.
		if($http_code != 200) 
			throw new Exception('Error : Failed to receieve access token');
		
		//Return the data array.
		return $data;
	}

	//A function to fetch an array of user data. This takes the access token as a parameter.
	public function GetUserProfileInfo($access_token) {	
		
		//Return the file content of the user info URL, using our access token as a unique user identifier.
		return file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token);
	}
}
?>