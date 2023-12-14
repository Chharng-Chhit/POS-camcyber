<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'order_status';

    public function order(){
        return $this->hasMany(Order::class, 'status_id');
    }
}
