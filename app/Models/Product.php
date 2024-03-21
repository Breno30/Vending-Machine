<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'image'];

    protected static function boot() {
        parent::boot();

        static::deleting(function ($product) {
            if (!$product->image ||
                !Storage::disk('local')->exists($product->image)) {
                return;
            }

            Storage::delete($product->image);
        });
    }

    public function machines() {
        return $this->belongsToMany(Machine::class)->withPivot('quantity');
    }
}
