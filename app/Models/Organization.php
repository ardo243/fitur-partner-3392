<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'pic_name',
        'email',
        'password',
        'phone',
        'logo',
        'description',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}