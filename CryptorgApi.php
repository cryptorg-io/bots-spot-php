<?php

class CryptorgApi {

    const API_URL = 'https://api.cryptorg.net';

    private $apiKey;
    private $apiSecret;

    public function __construct($key, $secret)
    {
        $this->apiKey = $key;
        $this->apiSecret = $secret;
    }

    /**
     * _________________________________________________________________________________________________________________
     *
     *                                              Public methods
     * _________________________________________________________________________________________________________________
     */

    /**
     * Get Api status
     * @return array
     */
    public function status()
    {
        return $this->sendRequest('GET', 'api/status');
    }

    /**
     * _________________________________________________________________________________________________________________
     *
     *                                            Methods with bots
     * _________________________________________________________________________________________________________________
     */

    /**
     * Get all user's bots
     * @return array
     */
    public function botList() {

        return $this->sendRequest('GET', 'bot/all');
    }

    /**
     * Get bot details
     * @param array $params
     * @return array
     */
    public function botInfo($params)
    {
        return $this->sendRequest('GET', 'bot/info', $params);
    }

    /**
     * Delete bot by id
     * @param array $params
     * @return array
     */
    public function deleteBot($params)
    {
        return $this->sendRequest('GET', 'bot/delete', $params);
    }

    /**
     * Create new bot
     * @param array $params Query params
     * @param array $attributes Post params
     * @return array
     */
    public function createBot($params, $attributes) {

        return $this->sendRequest('POST', 'bot/create', $params, $attributes);
    }

    /**
     * Create custom bot via preset
     * @param $params
     * @param $attributes
     * @return array
     */
    public function createBotPreset($params, $attributes)
    {
        return $this->sendRequest('POST', 'bot/create-preset', $params, $attributes);
    }

    /**
     * Create new bot
     * @param array $params Query params
     * @param array $attributes Post params
     * @return array
     */
    public function updateBot($params, $attributes)
    {
        return $this->sendRequest('POST', 'bot/configure', $params, $attributes);
    }

    /**
     * Activate Bot
     * @param array $params
     * @return array
     */
    public function activateBot($params)
    {
        return $this->sendRequest('GET', 'bot/activate', $params);
    }

    /**
     * Start bot force
     * @param $params
     * @return array
     */
    public function startBotForce($params)
    {
        return $this->sendRequest('GET', 'bot/start-force', $params);
    }

    /**
     * Deactivate Bot
     * @param array $params
     * @return array
     */
    public function deactivateBot($params)
    {
        return $this->sendRequest('GET', 'bot/deactivate', $params);
    }

    /**
     * Get bot logs
     * @param array $params
     * @return array
     */
    public function getBotLogs($params = null)
    {
        return $this->sendRequest('GET', 'bot/logs', $params);
    }

    /**
     * _________________________________________________________________________________________________________________
     *
     *                                              Methods with deals
     * _________________________________________________________________________________________________________________
     */

    /**
     * Freeze deal by id
     * @param array $params
     * @return array
     */
    public function freezeDeal($params)
    {
        return $this->sendRequest('GET', 'deal/freeze', $params);
    }

    /**
     * Unfreeze deal by id
     * @param array $params
     * @return array
     */
    public function unFreezeDeal($params)
    {
        return $this->sendRequest('GET', 'deal/unfreeze', $params);
    }

    /**
     * Update take profit of deal
     * @param array $params
     * @return array
     */
    public function updateTakeProfit($params)
    {
        return $this->sendRequest('GET', 'deal/update-take-profit', $params);
    }

    /**
     * Cancel a deal by id
     * @param array $params
     * @return array
     */
    public function cancelDeal($params)
    {
        return $this->sendRequest('GET', 'deal/cancel', $params);
    }

    /**
     * Cancel a deal by id
     * @param array $params
     * @return array
     */
    public function dealInfo($params)
    {
        return $this->sendRequest('GET', 'deal/info', $params);
    }

    /**
     * Get all user's analytic records
     * @param array $params
     * @return array
     */
    public function getAnalytics($params = null)
    {
        return $this->sendRequest('GET', 'analytics/get', $params);
    }

    /**
     * _________________________________________________________________________________________________________________
     *
     *                                                  System methods
     * _________________________________________________________________________________________________________________
     */

    /**
     * Create request to API
     * @param string $method
     * @param string $url
     * @param string | null $params
     * @param string | null $attributes
     * @return array
     */
    private function sendRequest($method, $url, $params = null, $attributes = null) {

        $query = json_encode($params == null ?  '' : http_build_query($params));
        $query = str_replace('"', '', $query);

        $nonce = round(microtime(true) * 1000);

        $strForSign = $url . '/' . $nonce . '/' . $query;

        $hash = hash_hmac('sha256', base64_encode($strForSign) , $this->apiSecret);

        $header = [

            "CTG-API-SIGNATURE: $hash",
            "CTG-API-KEY: " . $this->apiKey,
            "CTG-API-NONCE: $nonce"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::API_URL . '/' . $url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $attributes);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
}
