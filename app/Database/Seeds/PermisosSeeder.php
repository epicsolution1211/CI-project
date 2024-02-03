<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermisosSeeder extends Seeder
{
    public function run()
    {
        $data[0] = [
            'modulo'    => 'usuarios',
            'campos'    => json_encode(['listar usuarios', 'nuevo usuario', 'editar usuario', 'eliminar usuario']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[1] = [
            'modulo'    => 'configuracion',
            'campos'    => json_encode(['actualizar empresa', 'backup']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[2] = [
            'modulo'    => 'roles',
            'campos'    => json_encode(['listar roles', 'nuevo rol', 'editar rol', 'eliminar rol']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[3] = [
            'modulo'    => 'clientes',
            'campos'    => json_encode(['listar clientes', 'nuevo cliente', 'editar cliente', 'eliminar cliente']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[4] = [
            'modulo'    => 'prestamos',
            'campos'    => json_encode(['nuevo prestamo', 'historial prestamos', 'ver prestamo', 'eliminar prestamo', 'abono prestamo']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[5] = [
            'modulo'    => 'vehiculos',
            'campos'    => json_encode(['listar vehiculos', 'nuevo vehiculo', 'editar vehiculo', 'eliminar vehiculo']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[6] = [
            'modulo'    => 'fabricantes',
            'campos'    => json_encode(['listar fabricantes', 'nuevo fabricante', 'editar fabricante', 'eliminar fabricante']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[7] = [
            'modulo'    => 'modelos',
            'campos'    => json_encode(['listar modelos', 'nuevo modelo', 'editar modelo', 'eliminar modelo']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[8] = [
            'modulo'    => 'cajas',
            'campos'    => json_encode(['ver saldo']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        $data[9] = [
            'modulo'    => 'reportes',
            'campos'    => json_encode(['pdf prestamos', 'excel prestamos']),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        

        for ($i=0; $i < count($data); $i++) { 
            $this->db->table('permisos')->insert($data[$i]);
        }
        
    }
}
