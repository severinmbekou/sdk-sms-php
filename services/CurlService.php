<?php
	class CurlService {
		/*
		 * App ID du développeur.
 		 */
		 protected $appId;
		 protected $baseUrl;
		 protected $accessToken;
		 protected $oauthData;
		 protected $simpleSmsData;
		 public function __construct($baseUrl, $appId)
		 {
			 $this->baseUrl = $baseUrl;
			 $this->appId = $appId;
		 }

		 public function setOauthData ($oauthData)
		 {
			$this->oauthData = $oauthData;
		 }

		 public function setSimpleSmsData ($accessToken, $simpleSmsData)
		 {
			$this->accessToken = $accessToken;
			$this->simpleSmsData = $simpleSmsData;
		 }

		/*
		* Requête d'authentification du développeur
		* pour récupération de l'ACCESS TOKEN de sécurité.
		* methode: POST
		*/
		 public function authenticate()
		 {
			 $ch = curl_init();
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			 curl_setopt($ch, CURLOPT_URL, $this->baseUrl.'/v1/token');						// URL du service OAuth d'eashmobileapi.
			 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );		
			 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json') );								// On spécifi la requête post.
			 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->oauthData));	// Coordonnées du développeur.
			 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

			 $result = curl_exec($ch);												// On exécute la requête

			 $curl_errno = curl_errno($ch);											// Récupération du code d'erreur renvoyé par curl.
			 $curl_error = curl_error($ch);											// Description de l'érreur renvoyé par curl.
			 curl_close($ch);														// Fermeture de la requête curl.

			 if ($curl_errno > 0) {
				 echo "{'error': 'YES', 'code': $curl_errno, 'message': $curl_error}"; // Handle error here
			 } else {
				return $result;														// On retourne le résultat renvoyé par l'API.
			 }
		  }

	   /*
		* Requête d'envoie de sms.
		* methode: POST
		*/
		public function requestSendSimpleSms()
		 {
			$headers= array(
				'Content-Type: application/json', 
				'Authorization: Bearer '.$this->accessToken);	

			 $sendSms = curl_init();
			 curl_setopt($sendSms, CURLOPT_SSL_VERIFYPEER, 1);
			 curl_setopt($sendSms, CURLOPT_SSL_VERIFYHOST, 2);
			 curl_setopt($sendSms, CURLOPT_URL, $this->baseUrl.'/v1/sms/simple');		// URL de demande de payment.
			 curl_setopt($sendSms, CURLOPT_CUSTOMREQUEST, 'POST' );		
			 curl_setopt($sendSms, CURLOPT_HTTPHEADER, $headers);								// On spécifi la requête post.
			 curl_setopt($sendSms, CURLOPT_POSTFIELDS, json_encode($this->simpleSmsData));	// Coordonnées du développeur.
			 curl_setopt($sendSms,CURLOPT_RETURNTRANSFER,true);
			 
			 $result = curl_exec($sendSms);

			 $curl_errno = curl_errno($sendSms);
			 $curl_error = curl_error($sendSms);
			 curl_close($sendSms);

			 if ($curl_errno > 0) {
				 echo "{'error': 'YES', 'code': $curl_errno, 'message': $curl_error}";
			 } else {
				return $result;												// On retourne le résultat renvoyé par l'API.
			 }
		 }

	
	   /*
		* Requête interogation du solde sms.
		* methode: GET
		*/
		public function requestGetSmsBalance($accessToken)
		{
			$headers= array('Authorization: Bearer '.$accessToken);	

			 $balance = curl_init();
			 curl_setopt($balance, CURLOPT_SSL_VERIFYPEER, 1);
			 curl_setopt($balance, CURLOPT_SSL_VERIFYHOST, 2);
			 curl_setopt($balance, CURLOPT_URL, $this->baseUrl.'/balance/'.$this->appId.'/messaging');		// URL de demande de payment.
			 curl_setopt($balance, CURLOPT_CUSTOMREQUEST, 'GET' );		
			 curl_setopt($balance, CURLOPT_HTTPHEADER, $headers);								// On spécifi la requête post.
			 curl_setopt($balance,CURLOPT_RETURNTRANSFER,true);
			 
			 $result = curl_exec($balance);

			 $curl_errno = curl_errno($balance);
			 $curl_error = curl_error($balance);
			 curl_close($balance);

			 if ($curl_errno > 0) {
				 echo "{'error': 'YES', 'code': $curl_errno, 'message': $curl_error}";
			 } else {
				return $result;												// On retourne le résultat renvoyé par l'API.
			 }
		 }
	}
?>
