<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pedido extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'pedidos';

    protected $fillable = [
        'id',
        'codigo_rede',
        'cliente_id', 
        'nome',
        'email',
        'telefone',
        'cpf',
        'rg',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'status',
        'codigo_rastreio',
        'created_at',
        'updated_at'
    ];

}
