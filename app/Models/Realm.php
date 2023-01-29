<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class, "name", "realm");
    }
}
