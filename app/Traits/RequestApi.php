<?php

namespace App\Traits;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;
trait RequestApi
{
    protected $client;

    protected static $apiBaseUrl ='';

    public function __construct()
    {
        $this->client = new Client();
         if (empty(self::$apiBaseUrl)) {
            self::$apiBaseUrl = env('API_BASE_URL', 'https://dummyjson.com/');
        }

        $this->LogintoApi();
    }

    

    protected function sendGetRequest(string $endpoint, string $token)
    {
        try {
            $url  = self::$apiBaseUrl . $endpoint;
            $response = $this->client->request('GET', $url, [
               
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
            ]);
             $statusCode = $response->getStatusCode();
            
            
             
                return [
                    'statusCode' => $statusCode,
                    'data' => json_decode($response->getBody()->getContents(), true)
                ];
         
        } catch (RequestException $e) {
           return [
            'statusCode' => 404,
            'error' => 'Request failed',
            'message' => $e->getMessage(),
        ];
        }
    }

    protected function sendPostRequest(string $endpoint, array $data, array $headers = [])
    {
        try {
             $url  = self::$apiBaseUrl . $endpoint;
            $response = $this->client->request('POST', $url, [
                'json' => $data,
                'headers' => $headers,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            //return $e->getMessage();
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    protected function LogintoApi()
    {
        
         $data = [
            'username' => 'emilys',
            'password' => 'emilyspass',
        ];
        if (!empty($this->getToken())) return;
            
        $response = $this->sendPostRequest('auth/login', $data);
        if ($response && isset($response['accessToken']) && !empty($response['accessToken'])) {
            $this->setToken($response); 
        } else {
            return response()->json(['error' => 'Failed to login or retrieve token'], 403);  // Use 403 here
        }
       
        
    }

    protected function setToken($data) {
         $accessToken = $data['accessToken'];
         Cache::put('access_token', $accessToken, now()->addDays(1));
    }

    protected function getToken()
    {
        return Cache::get('access_token');
    }

    protected function getDefaultProductApi() 
    {
        $token = $this->getToken();
        $response = $this->sendGetRequest('products', $token);
        if ($response['statusCode'] !==  200) {
            return response()->json($response, $response['statusCode']);
        }
        return $response['data'];
    }

    protected function getDefaultProductApiCache() 
    {
        return Cache::remember('default_product_cache', 3600, function () {
            return $this->getDefaultProductApi();
        });
    }

    protected function getHomepageProduct() 
    {
        if (env('WILLUSECACHING') == 1) {
            return $this->getDefaultProductApiCache();
        }

        return $this->getDefaultProductApi();
    }

    protected function getProductDetailApi($id)
    {
        $token = $this->getToken();
        $response =  $this->sendGetRequest('products/' . $id, $token);
            if ($response['statusCode'] !==  200) {
                return response()->json($response, $response['statusCode']);
            }
        return $response['data'];
    }

    protected function getProductDetailApiCache($id) {
        return Cache::remember('product_detail_cache_'.$id, 3600, function () use ($id){
            return $this->getProductDetailApi($id);
        });
    }

    protected function getProdDetail($id)
    {
        if (env('WILLUSECACHING') == 1) {
            return $this->getProductDetailApiCache($id);
        }
        return $this->getProductDetailApi($id);
    }


}


?>