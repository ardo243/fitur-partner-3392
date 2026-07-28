<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'logo_url'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}