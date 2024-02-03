<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'identidad' => '123456789',
            'nombre'    => 'GRINBIN.IO',
            'telefono'    => '900897537',
            'correo'    => 'leonardo@grinbin.io',
            'direccion'    => 'México',
            'mensaje'    => 'GRACIAS POR ADQUIRIR EL SISTEMA',
            'tasa_interes'    => '10',
            'cuotas'    => '18',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Using Query Builder
        $this->db->table('configuracion')->insert($data);
    }
}
