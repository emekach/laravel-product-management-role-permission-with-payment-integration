<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
