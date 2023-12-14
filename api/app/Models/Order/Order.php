<?php

namespace App\Models\Order;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User\User;
use App\Models\Order\Detail;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';

    public function cashier() // M:1
    { 
        return $this->belongsTo(User::class, 'cashier_id')
        ->select('id', 'name');
    }

    public function details()// 1:M
    { 
        return $this->hasMany(Detail::class, 'order_id')
         ->select('id', 'order_id', 'qty', 'product_id', 'unit_price', 'total_price_this_product')
        ->with([
            'product:id,name,image'
        ])
        ;
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
