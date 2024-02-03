<?php

namespace App\Models;

use CodeIgniter\Model;

class VehiculosModel extends Model
{
    protected $table            = 'vehiculos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'vin', 'id_fabricante', 'id_modelo','color', 'categoria',
        'transmision', 'motor', 'año', 'kilometraje', 'numero_puertas', 'precio', 'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $updatedField  = 'updated_at';

     // Validation
     protected $validationRules      = [
        'id_vehiculo' => 'is_natural',
        'vin'    => [
            'rules'  => 'required|min_length[17]|is_unique[vehiculos.vin,id,{id_vehiculo}]',
            'errors' => [
                'required' => 'El N° de identificación vehícular es obligatorio',
                'min_length' => 'El N° de identidad debe contener 17 caracteres',
                'is_unique' => 'El N° de identidad debe único',
            ],
        ]
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
