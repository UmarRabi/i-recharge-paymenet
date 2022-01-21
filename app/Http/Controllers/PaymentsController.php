<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Customers;
use App\Models\CustomerCards;
use App\Models\CustomerTransactionsLog;

class PaymentsController extends Controller
{
    public function initiate(Request $request, $id){
    	$card=CustomerCards::with('customer')->where('id', $id)->get()->first();
    	$key= env("FLUTTER_ENC_KEY");
    	$url=env('FLUTTER_BASE_URL').'charges?type=card';
    	$data=[
   			"card_number"=>$card->card_no,
		  	"cvv"=>$card->cvv,
		  	"expiry_month"=>$card->expiry_day,
		   	"expiry_year"=>$card->expiry_year,
		   	"currency"=>"NGN",
		   	"amount"=>$request->amount,
		   	"email"=>$card->customer->email,
		   	"fullname"=>$card->customer->first_name." ".$card->customer->last_name,
		   	"tx_ref"=>uniqid(),
		   	"redirect_url"=>"https://webhook.site/3ed41e38-2c79-4c79-b455-97398730866c",
		   	"authorization" => [
     			"mode"=> "pin",
     			"pin"=> $card->pin
     		]
		];
    	$enc=self::encrypt3Des(json_encode($data), $key);
    	$body=[
    		'client'=>$enc
    	];

    	$initiate=self::curlPost($url, $body);
    	if($initiate["status"]=="success"){
    		$response=$initiate;
    	}else{
    		$response=[
    			'status'=>400,
    			'message'=>'charge initialization failed',
    			'data'=>'something went wrong'
    		];
    	}
    	return $response;
    }

    public function validateCharge(Request $request){
    	$url=env('FLUTTER_BASE_URL').'validate-charge?type=card';
    	$body=[
    		'otp'=>$request->otp,
    		'flw_ref'=>$request->flw_ref
    	];
    	$validate=self::curlPost($url, $body);
    	//return $validate['status'];
    	if($validate["status"]=="success"){
    		self::log($validate);
    		return $validate;
    	}

    	return [
    		'status'=>400,
    		'message'=>'something went wrong'
    	];
    }

    //flutter encryption
   	public function encrypt3Des($data, $key){
  		$encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
    	return base64_encode($encData); 
 	}

 	public function curlPost($url, $body){
 		try{
 			$header=[
	    		'Content-Type'=>'application/json',
	    		'Authorization'=>'Bearer '.env("FLUTTER_PRIVATE_KEY")
	    	];
 			return Http::withHeaders($header)->post($url, $body);
 		}catch(Exception $e){
 			return var_dump($e);
 		}
 		
 	}

 	public function log($validate){
 		$customer=Customers::where('email', $validate['data']['customer']['email'])->get()->first();
 		$log=new CustomerTransactionsLog();
 		$log->customer_id=$customer->id;
 		$log->amount=$validate['data']['amount'];
 		$log->ref=$validate['data']['flw_ref'];
 		$log->description=$validate['message'];
 		$log->save();
 	}
}
