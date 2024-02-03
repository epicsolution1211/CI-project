<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nombre'    => 'Leonardo',
            'apellido'    => 'Grinn',
            'telefono'    => '6181023360',
            'correo'    => 'leonardo@grinbin.io',
            'direccion'    => 'México',
            'clave'    => password_hash('admin123456789', PASSWORD_DEFAULT),
            'verify'    => '1',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
            'id_rol'    => 1,
        ];
        // Using Query Builder
        $this->db->table('usuarios')->insert($data);
    }
}
