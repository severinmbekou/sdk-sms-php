<?php
require("CurlService.php"); // Necessité d'utiliser curl pour les requêtes externes.

/*
* SDK LEUTON GROUP
* Version V0.8
*/

class CustumAPI
{
    /*
    * Identification du développeur(client id).
    */
    private $appKey;
    /*
    * Clé secrète du développeur(client secret).
    */
    private $appSecret;

    // URL du serveur OAuth de 3ibusinessapi.
    private $BASE_URL = "http://localhost:8062";

    private $curlService;                // L'instance curl utilisé pour faire les requêtes post.

    private $accessToken;
    private $refreshToken;                // Refresh token à utiliser lors de l'expiration du token actuel.

    private $tokenDate;                    // Date d'expiration du token actuellement utiliser.


    // Objet singleton servant à faire les requêtes ves le serveur.
    public static $instance = NULL;

    /**
     * Object singleton permettant de conserver l'état de  l'utilisation
     * de l'API:
     * les coordonnées du dévelopeur, l'access token, le refresh token,
     * la date d'expirationd du token et les url à appeller.
     */
    public static function getInstance($appKey, $appSecret, $appId)
    {
        if (static::$instance === NULL) {
            static::$instance = new CustumAPI();

            static::$instance->appKey = $appKey;
            static::$instance->appSecret = $appSecret;
            static::$instance->accessToken = "0000000000";
            static::$instance->refreshToken = "0000000000";
            static::$instance->tokenDate = time();

            static::$instance->curlService = new CurlService(static::$instance->BASE_URL, $appId);
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /*
    * <p>Authentification du developpeur à l'aide de ses credentials.</p>
    * Et Mise à jour automatique du access token lors de son expiration
    */
    public function oauthAuthenticate()
    {
        // Date courante pour tester si le token de sécurité est expiré.
        $currentDate = time();

        // Si le token a déjà été récupéré pour la première on le revoi s'il n'est pas expiré.
        if (static::$instance->tokenDate < $currentDate) {
            // On retourne le token puisqu'il n'est pas expiré.
            return static::$instance->accessToken;
        } else {
            // Request a new token
            static::$instance->curlService->setOauthData(array("appKey" => static::$instance->appKey, "appSecret" => static::$instance->appSecret));
            // call Autentification endpoint.
            $response = $this->curlService->authenticate();
            $jsonResponse = json_decode($response);

            // Récupération du token de sécurité.
            $developerAccessToken = $jsonResponse->access_token;

            static::$instance->tokenDate = time() + $jsonResponse->expires_in;  // initialize the new expiration date of token.
            static::$instance->accessToken = $developerAccessToken;
            //	static::$instance->refreshToken =  $jsonResponse->refresh_token;
            return static::$instance->accessToken;
        }

    }

    /*
    * Demande denvoie de sms unique a plusieurs numeros.
    */
    public function requestSimpleSms($destinataires, $message)
    {
        static::$instance->curlService->setSimpleSmsData(static::$instance->accessToken, array("to" => $destinataires, "body" => $message));

        $response = static::$instance->curlService->requestSendSimpleSms(); // Demande d'envoie de sms.
        $jsonResponse = json_decode($response);                            // Décodage du json renvoyé.

        //echo $jsonResponse;
        return $response;
    }

    /*
    * Demande de solde adréssé à un client possédant un numéro de téléphone.
    */
    public function requestGetSmsBalance()
    {
        // echo "ACCESS TOKEN IS:" . static::$instance->accessToken;
        $response = static::$instance->curlService->requestGetSmsBalance(static::$instance->accessToken); // Demande d'envoie de sms.
        $jsonResponse = json_decode($response);                            // Décodage du json renvoyé.

        //echo $jsonResponse;
        return $response;
    }
}

?>
 
