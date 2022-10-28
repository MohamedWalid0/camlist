<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Pet extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    const STATUS_AVAILABLE = 'available' ;
    const STATUS_PENDING = 'pending' ;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function userBids(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bids');
    }

    public function winners(Pet $pet)
    {
        return $pet->bids->sortByDesc('cost'); 
    }









}
