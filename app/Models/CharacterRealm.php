<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterRealm extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_name',
        'realm_name',
        'character-name_realm-name',
    ];

    public function character()
    {
        return $this->hasOne(Character::class, "name", "character_name");
    }
    public function realm()
    {
        return $this->hasOne(Realm::class, "name", "realm_name");
    }
}
