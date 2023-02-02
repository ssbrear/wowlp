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
        'realm_slug',
        'character_name_realm_name',
        'race',
        'class',
        'guild',
        'headshot',
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
