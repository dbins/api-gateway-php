<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model
{

    use HasApiTokens, Authenticatable, Authorizable;
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'password'
    ];

    protected $hidden = ['password'];
}
