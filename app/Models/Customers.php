<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table="customers";

    public function cards(){
    	return $this->hasMany('App\Models\CustomerCards', 'customer_id', 'id');
    }

    public function payments(){
    	return $this->hasMany('App\Models\CustomerTransactionsLog', 'customer_id', 'id');
    }
}
