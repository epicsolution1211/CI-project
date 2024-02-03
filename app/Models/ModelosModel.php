<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelosModel extends Model
{
    protected $table            = 'modelos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_fabricante', 'nombre_modelo', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $updatedField  = 'updated_at';

     // Validation
     protected $validationRules      = [
        'id_modelo' => 'is_natural',
        'nombre_modelo'    => [
            'rules'  => 'required|min_length[3]|is_unique[modelos.nombre_modelo,id,{id_modelo}]',
            'errors' => [
                'required' => 'El Nombre del Modelo es obligatorio',
                'min_length' => 'El Nombre de Fabricante debe tener 3 caracteres',
            ],
        ]
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
