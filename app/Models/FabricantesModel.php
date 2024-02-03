<?php

namespace App\Models;

use CodeIgniter\Model;

class FabricantesModel extends Model
{
    protected $table            = 'fabricantes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre_fabricante', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $updatedField  = 'updated_at';

     // Validation
     protected $validationRules      = [
        'id_fabricante' => 'is_natural',
        'nombre_fabricante'    => [
            'rules'  => 'required|min_length[3]|is_unique[fabricantes.nombre_fabricante,id,{id_fabricante}]',
            'errors' => [
                'required' => 'El Nombre del Fabricante es Obligatorio',
                'min_length' => 'El Nombre de Fabricante debe tener 3 caracteres',
            ],
        ]
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
