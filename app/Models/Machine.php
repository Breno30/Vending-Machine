<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'latitude', 'longitude', 'owner_id'];

    /**
     * Get the owner of the machine.
     */
    public function owner() {
        return $this->belongsTo(User::class);
    }

        /**
     * Get the products for the machine.
     */
    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
