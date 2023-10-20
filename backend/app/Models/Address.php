<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'adresses';

    protected $fillable = [
        'zip_code',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'uf',
    ];

    public static $rules = [
        'zip_code' => 'required',
        'street' => 'required',
        'district' => 'required',
        'city' => 'required',
        'uf' => 'required',
    ];

    public static $messages = [
        'zip_code.required' => 'O Código Postal é obrigatório.',
        'street.required' => 'O Logradouro é obrigatório.',
        'district.required' => 'A Comunidade ou Região é obrigatória.',
        'city.required' => 'A cidade é obrigatória.',
        'uf.required' => 'O estado é obrigatório.',
    ];
}
