<?php
namespace App\Pet\Send;

class SendData
{
    private $patch;
    private $data;
    private $customerRequest;
    private $apiKey;

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setCustomerRequest($customerRequest)
    {
        $this->customerRequest = $customerRequest;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function setPatch(string $patch): void
    {
        $this->patch = $patch;
    }

    public function send()
    {


        $curl = curl_init();

        $headers = ['Content-Type: application/json'];
        if (!empty($this->apiKey)) {
            $headers[] = 'api_key: ' . $this->apiKey;
        }

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://petstore.swagger.io/v2/' . $this->patch,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->customerRequest,
            CURLOPT_POSTFIELDS =>$this->data,
            CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            return [ 'response' => $response, 'http_code' => $httpCode ];
    }


}
