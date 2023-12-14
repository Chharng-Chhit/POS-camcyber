<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;
    protected $table = 'products_type';

    public function products(): HasMany //1:M
    {
        return $this->hasMany(Product::class, 'type_id');
    }
}
