<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicin_id',
        'user_id',
    ];

    public function medicin()
    {
        return $this->belongsTo(Medicine::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
