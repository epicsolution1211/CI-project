<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestamosModel extends Model
{
    protected $table            = 'prestamos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['vehiculo', 'valor_vehiculo', 'importe', 'enganche', 'modalidad', 'tasa_interes', 'cuotas', 'fecha', 'fecha_venc', 'estado', 'id_cliente', 'id_vehiculo', 'id_usuario', 'saldo_pendiente', 'interes_total'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_cliente' => 'required',
        'cliente' => 'required',
        'importe'    => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'El importe credito es requerido'
            ],
        ],
        'modalidad' => 'required',
        'tasa_interes' => 'required',
        'cuotas' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
