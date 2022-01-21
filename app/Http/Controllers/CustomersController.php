<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\CustomerCards;

class CustomersController extends Controller
{
    public function createCustomer(Request $request){
    	try{
    		$validatedData = $request->validate([
	    		'first_name' => ['required'],
	    		'last_name' => ['required'],
	    		'email'=>['required'],
	    		'gender'=>['required'],
	    		'dob'=>['required']
			]);

    		if(count(Customers::where('email', $request->email)->get())>0){
    			return [
					'status'=>400,
					'message'=>'customer already exists',
				];
    		}
			$customer=new Customers();
			$customer->first_name=$request->first_name;
			$customer->last_name=$request->last_name;
			$customer->email=$request->email;
			$customer->gender=$request->gender;
			$customer->dob=date($request->dob);
			$customer->wallet=0;
			if(isset($request->phone)){
				$customer->phone=$request->phone;
			}

			$customer->save();
			return [
				'status'=>200,
				'message'=>'customer created successfully',
				'body'=>$customer
			];
    	}catch(Exception $e){
    		return $e->getMessage();	
    	}
    	
    }

    public function createCard(Request $request, $id){
    	try{
    		$validatedData = $request->validate([
	    		'card_no' => ['required'],
	    		'cvv' => ['required'],
	    		'expire_date'=>['required'],
	    		'pin'=>['required']
			]);

    		if(count(CustomerCards::where('card_no', $request->card_no)->get())>0){
    			return [
					'status'=>400,
					'message'=>'card already added',
				];
    		}
    		$expire_details=explode('/', $request->expire_date);
    		if(count($expire_details)<=0){
    			return [
					'status'=>400,
					'message'=>'not a valid expiry date (it should be in the format mm/yy)',
				];	
    		}
			$card=new CustomerCards();
			$card->card_no=$request->card_no;
			$card->cvv=$request->cvv;
			$card->expiry_day=$expire_details[0];
			$card->expiry_year=$expire_details[1];
			$card->pin=$request->pin;
			$card->customer_id=$id;
			$card->save();
			return [
				'status'=>200,
				'message'=>'card added successfully',
				'body'=>$card
			];
    	}catch(Exception $e){
    		return $e->getMessage();	
    	}
    }

    public function getCustomer($id){
    	$customer=Customers::with('cards', 'payments')->where('id', $id)->get()->first();
    	if(!$customer){

    	}

    	return [
    		'status'=>200,
    		'message'=>'record found',
    		'data'=>$customer
    	];
    }

    public function all(){
    	return "this is the all api";
    }
}
