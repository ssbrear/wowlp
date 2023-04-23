<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battletag extends Model
{
    use HasFactory;

    protected $fillable = [
        'battletag',
    ];

    public function praises()
    {
        return $this->hasMany(Prase::class, 'praiser_id');
    }
}
