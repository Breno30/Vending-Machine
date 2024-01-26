<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'image'];

    public function machines() {
        return $this->belongsToMany(Machine::class)->withPivot('quantity');
    }
}
