<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'realm',
        'race',
        'class',
        'guild',
    ];

    public function realm()
    {
        return $this->belongsTo(Realm::class, "realm", "name");
    }
    public function characterRealm()
    {
        return $this->hasOne(CharacterRealm::class, "character_name", "name");
    }

}
