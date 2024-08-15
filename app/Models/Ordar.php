<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordar extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicin_id',
        'user_id',
        'name',
        'quantity',
        'status',
        'Payment_status'
    ];

    public function medicin()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
