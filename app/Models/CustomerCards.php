<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCards extends Model
{
    use HasFactory;
    protected $table='customer_cards';

    public function customer(){
    	return $this->hasOne('App\Models\Customers', 'id', 'customer_id');
    }
}
