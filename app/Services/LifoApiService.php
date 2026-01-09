<?php

namespace App\Services;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Auth;

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
        $DATA = [
            'USER_ID' => $USER_ID,
            'PASSWORD' => $PASSWORD,
        ];
        $response = Http::post($this->url . 'OcUser/GetToken', $DATA);
        $this->logApiCall('getAuth', $DATA, $response);
        return $response;
    }



    public  function issuingPolicy($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/NewPolicy', $DATA);
        $this->logApiCall('issuingPolicy', $DATA, $response);
        return $response;
    }

    public  function cancelPolicy($headers, $DATA)
    {
        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/PolicyStatusChange', $DATA);
        $this->logApiCall('cancelPolicy', $DATA, $response);
        return $response;
    }


    public  function policystatus($headers, $DATA)
    {

      

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcPolicy/OCPolStatus', $DATA);
        $this->logApiCall('policystatus', $DATA, $response);
        return $response;
    }
// 
    public  function newrequestadmin($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/NewUORequest', $DATA);
        $this->logApiCall('newrequestadmin', $DATA, $response);
        return $response;
    }

    public  function requeststatusadmin($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/UoRequestStatus', $DATA);
        $this->logApiCall('requeststatusadmin', $DATA, $response);
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
        $this->logApiCall('addcardsadmin', $DATA, $response);
        return $response;
    }

    public  function postInsCompCertificateBook($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OrangeCardServices/PostInsCompCertificateBook', $DATA);
        $this->logApiCall('postInsCompCertificateBook', $DATA, $response);
        return $response;
    }

    
    //
    public  function newrequest($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/NewICRequest', $DATA);
        $this->logApiCall('newrequest', $DATA, $response);
        return $response;
    }

    public  function requeststatus($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/IcRequestStatus', $DATA);
        $this->logApiCall('requeststatus', $DATA, $response);
        return $response;
    }

    public  function addcards($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/IcOcSerialRequest', $DATA);
        $this->logApiCall('addcards', $DATA, $response);
        return $response;
    }


    

    public  function printcard($headers, $DATA)
    {

        $response = Http::withHeaders($headers)
            ->post($this->url . 'OcRequest/OCCertificate', $DATA);
        $this->logApiCall('printcard', $DATA, $response);
        return $response;
    }

    function extractStatus($statusMessage)
{
    // Regular expression to match the status
    $pattern = '/\b(Submitted|Approved|Rejected)\b/';
    preg_match($pattern, $statusMessage, $matches);

    return isset($matches[1]) ? $matches[1] : null;
}

    private function logApiCall($operation_type, $data, $response, $username = null)
    {


        $status = $response->successful() ? 'success' : 'failure';

        $body = $response->body();
        $psrResponse = $response->toPsrResponse();
        $stream = $psrResponse->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        ApiLog::create([
            'user_name' => $username ?? (Auth::check() ? Auth::user()->username : 'System'),
                'company_name' => Auth::check() ? Auth::user()->companies->name : null,
            'office_name' =>  Auth::user()->offices ? Auth::user()->offices->name : null,
            'operation_type' => $operation_type,
            'execution_date' => now(),
            'status' => $status,
            'sent_data' => $data,
            'received_data' => $body,
            'related_link' => null,
        ]);
    }

    
}
