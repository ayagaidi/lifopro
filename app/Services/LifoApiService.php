<?php

namespace App\Services;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class LifoApiService
{

    public $url;

    public function __construct()
    {
        
        // $this->url = 'http://197.44.140.211:83/api/';
                // $this->url = 'https://oc.gaif.org:83/api/';

                $this->url = 'http://197.44.140.211:83/api/';

        
    }

    public  function getAuth($USER_ID, $PASSWORD)
    {
        $response = Http::post($this->url . 'OcUser/GetToken', [
            'USER_ID' => $USER_ID,
            'PASSWORD' => $PASSWORD,
        ]);
        return $response;
    }



    public  function issuingPolicy($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/NewPolicy', $DATA);

        return $response;
    }

    public  function cancelPolicy($headers, $DATA)
    {
        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/PolicyStatusChange', $DATA);
        return $response;
    }


    public  function policystatus($headers, $DATA)
    {

      

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/OCPolStatus', $DATA);

        return $response;
    }
// 
    public  function newrequestadmin($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/NewUORequest', $DATA);

        return $response;
    }

    public  function requeststatusadmin($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/UoRequestStatus', $DATA);

        return $response;
    }


    // public  function addcardsadmin($headers, $DATA)
    // {

    //     $response = Http::withHeaders($headers)
    //         ->post($this->url . 'OcRequest/UoOcSerialRequest', $DATA);

    //     return $response;
    // }
    public  function addcardsadmin($headers, $DATA)
    {


        $response = Http::withHeaders($headers)->post($this->url . 'OcRequest/UoOcSerialRequest', $DATA);


        return $response;
    }

    public  function postInsCompCertificateBook($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OrangeCardServices/PostInsCompCertificateBook', $DATA);

        return $response;
    }

    
    // 
    public  function newrequest($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/NewICRequest', $DATA);

        return $response;
    }

    public  function requeststatus($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/IcRequestStatus', $DATA);

        return $response;
    }

    public  function addcards($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/IcOcSerialRequest', $DATA);

        return $response;
    }


    

    public  function printcard($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/OCCertificate', $DATA);

        return $response;
    }

    function extractStatus($statusMessage)
{
    // Regular expression to match the status
    $pattern = '/\b(Submitted|Approved|Rejected)\b/';
    preg_match($pattern, $statusMessage, $matches);

    return isset($matches[1]) ? $matches[1] : null;
}

    
}
