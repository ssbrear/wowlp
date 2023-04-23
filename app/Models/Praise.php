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
        'type',
        'description',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
    public function battletag()
    {
        return $this->belongsTo(Battletag::class, 'praiser_id', 'battletag');
    }
}
