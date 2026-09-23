<?php

namespace App;

class User extends \Illuminate\Foundation\Auth\User
{
    protected $fillable = ['name', 'email', 'password'];
}
