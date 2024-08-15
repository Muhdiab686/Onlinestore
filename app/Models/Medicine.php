<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'scientific_name',
        'commercial_name',
        'manufacture_company',
        'quantity',
        'expiry_date',
        'price',
        'category_id',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function favorite() : Hasmany
    {
        return $this->hasMany(Favorite::class);
    }

    public function order() : Hasmany
    {
        return $this->hasMany(Order::class);
    }
}
