<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RequestApi;
use Illuminate\Support\Facades\Cache;
class ProductController extends Controller
{
    //
    use RequestApi;

    public function getDefault()
    {
        return $this->getHomepageProduct();
       
    }

    public function getProductDetail($id)
    {

        return $this->getProdDetail($id);
      
    }

    public function searchProduct(Request $request) 
    {
        $token = $this->getToken();
        $search = $request->input('search');
        $response =$this->sendGetRequest('products/search?q='.urlencode($search),$token);
        if ($response['statusCode'] !==  200) {
            return response()->json($response, $response['statusCode']);
        }
        return $response['data'];
    }
    
}
