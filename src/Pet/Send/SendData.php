<?php
namespace App\Pet\Send;

class SendData
{
    private $patch;
    private $data;
    private $put;


    public function setPut($put)
    {
        $this->put = $put;
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

        $customerRequest = (!empty($this->put))?'PUT':'POST';

        $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://petstore.swagger.io/v2/' . $this->patch,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $customerRequest,
            CURLOPT_POSTFIELDS =>$this->data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            return [ 'response' => $response, 'http_code' => $httpCode ];
    }


}
