<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Entrega extends Authenticatable
{
    use Notifiable;

    protected $table = 'dados_entrega';

    protected $fillable = [
        'id',
        'dados_cliente_id',
        'cep',
        'endereco', 
        'numero',
        'complemento',
        'bairro',
        'lugar',
        'created_at',
        'updated_at'
    ];

}
