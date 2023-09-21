<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStock extends Model
{
    use HasFactory;

    protected $table = 'user_stock'; 

    protected $fillable = [
        'user_id',
        'stock_name',
        'stock_price',
        'stock_quantity',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
