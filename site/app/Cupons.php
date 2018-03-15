<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cupons extends Authenticatable
{
    use Notifiable;

    protected $table = 'cupons';

    protected $fillable = [
        'id',
        'codigo',
        'porcentagem',
        'status',
        'created_at',
        'updated_at'
    ];

}
