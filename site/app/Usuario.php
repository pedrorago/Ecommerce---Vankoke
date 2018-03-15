<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'dados_cliente';

    protected $fillable = [
        'nome_completo',
        'email',
        'cep',
        'telefone',
        'cpf',
        'dia_nascimento',
        'mes_nascimento',
        'ano_nascimento',
        'password',
        'token',
        'ip',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
}
