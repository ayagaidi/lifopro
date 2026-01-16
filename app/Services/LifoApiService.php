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

        // Determine related link based on operation type - use API endpoints
        $related_link = null;
        switch ($operation_type) {
            case 'getAuth':
                $related_link = $this->url . 'OcUser/GetToken';
                break;
            case 'issuingPolicy':
                $related_link = $this->url . 'OcPolicy/NewPolicy';
                break;
            case 'cancelPolicy':
                $related_link = $this->url . 'OcPolicy/PolicyStatusChange';
                break;
            case 'policystatus':
                $related_link = $this->url . 'OcPolicy/OCPolStatus';
                break;
            case 'newrequestadmin':
                $related_link = $this->url . 'OcRequest/NewUORequest';
                break;
            case 'requeststatusadmin':
                $related_link = $this->url . 'OcRequest/UoRequestStatus';
                break;
            case 'addcardsadmin':
                $related_link = $this->url . 'OcRequest/UoOcSerialRequest';
                break;
            case 'newrequest':
                $related_link = $this->url . 'OcRequest/NewICRequest';
                break;
            case 'requeststatus':
                $related_link = $this->url . 'OcRequest/IcRequestStatus';
                break;
            case 'addcards':
                $related_link = $this->url . 'OcRequest/IcOcSerialRequest';
                break;
            case 'postInsCompCertificateBook':
                $related_link = $this->url . 'OrangeCardServices/PostInsCompCertificateBook';
                break;
            case 'printcard':
                $related_link = $this->url . 'OcRequest/OCCertificate';
                break;
            default:
                $related_link = $this->url;
        }

        // Determine company_name and office_name based on user type
        $company_name = null;
        $office_name = null;
        
        if (Auth::guard('officess')->check()) {
            // Office user is logged in
            $officeUser = Auth::guard('officess')->user();
            $office_name = $officeUser->offices ? $officeUser->offices->name : null;
            $company_name = $officeUser->offices && $officeUser->offices->companies ? $officeUser->offices->companies->name : null;
                          $office_user_name = $officeUser->username;

        } elseif (Auth::guard('companys')->check()) {
            // Company user is logged in
            $companyUser = Auth::guard('companys')->user();
            $company_name = $companyUser->companies ? $companyUser->companies->name : null;
            $office_user_name =  null;
        } elseif (Auth::check()) {
            // Regular user is logged in
            $user = Auth::user();
            $company_name = $user->companies ? $user->companies->name : null;
            $office_name = $user->offices ? $user->offices->name : null;
                        $office_user_name =  null;

        }

        ApiLog::create([
            'user_name' => $username ?? (Auth::check() ? Auth::user()->username : (Auth::guard('officess')->check() ? Auth::guard('officess')->user()->username : (Auth::guard('companys')->check() ? Auth::guard('companys')->user()->username : (Auth::guard('admin')->check() ? Auth::guard('admin')->user()->username : 'System')))),
            'company_name' => $company_name,
            'office_name' => $office_name,
                                'office_user_name' => $office_user_name,

            'operation_type' => $operation_type,
            'execution_date' => now(),
            'status' => $status,
            'sent_data' => $data,
            'received_data' => $body,
            'related_link' => $related_link,
        ]);
    }

    
}
