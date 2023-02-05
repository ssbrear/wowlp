<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Praise extends Model
{
    use HasFactory;

    protected $fillable = [
        'praiser_id',
        'character_id',
        'pumper',
        'technical',
        'leader',
        'helpful',
        'attitude',
        'description',
    ];

    public function character() {
        return $this->belongsTo(Character::class);
    }
}
