<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VehiculosModel;
use App\Models\FabricantesModel;
use App\Models\ModelosModel;

class VehiculosController extends BaseController
{
    private $vehiculos, $fabricantes, $modelos, $session;

    public function __construct()
    {
        $this->vehiculos = new VehiculosModel();
        $this->fabricantes = new FabricantesModel();
        $this->modelos = new ModelosModel();
        helper(['form']);
        $this->session = session();
    }

    
    public function index()
    {
        if (!verificar('listar vehiculos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('vehiculos/index', $data);
    }

    public function listar()
    {
        $data = $this->vehiculos->where('estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo vehiculo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('vehiculos/nuevo', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo vehiculo', $this->session->permisos)){
            $data = [
                'id_vehiculo' => $this->request->getVar('id_vehiculo'),
                'vin' => $this->request->getVar('vin'),
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'id_modelo' => $this->request->getVar('id_modelo'),
                'color' => $this->request->getVar('color'),
                'categoria' => $this->request->getVar('categoria'),
                'transmision' => $this->request->getVar('transmision'),
                'motor' => $this->request->getVar('motor'),
                'año' => $this->request->getVar('año'),
                'kilometraje' => $this->request->getVar('kilometraje'),
                'numero_puertas' => $this->request->getVar('numero_puertas'),
                'precio' => $this->request->getVar('precio')
            ];
            if ($this->vehiculos->insert($data) === false) {
                $data['errors'] = $this->vehiculos->errors();
                $data['active'] = 'vehiculo';
                return view('vehiculos/nuevo', $data);
            }
            return redirect()->to(base_url('vehiculos'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'VEHÍCULO REGISTRADO',
            ]);
        }else{
            return view('permisos');
        }
              
    }

    public function edit($idVehiculo){
        if (!verificar('editar vehiculo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['vehiculo'] = $this->vehiculos->where('id', $idVehiculo)->first();
        $data['active'] = 'vehiculo';
        return view('vehiculos/edit', $data);
    }

    public function update($idVehiculo)
    {
        if ($this->request->is('put') && verificar('editar vehiculo', $this->session->permisos)){
            $data = [
                'id_vehiculo' => $this->request->getVar('id_vehiculo'),
                'vin' => $this->request->getVar('vin'),
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'id_modelo' => $this->request->getVar('id_modelo'),
                'color' => $this->request->getVar('color'),
                'categoria' => $this->request->getVar('categoria'),
                'transmision' => $this->request->getVar('transmision'),
                'motor' => $this->request->getVar('motor'),
                'año' => $this->request->getVar('año'),
                'kilometraje' => $this->request->getVar('kilometraje'),
                'numero_puertas' => $this->request->getVar('numero_puertas'),
                'precio' => $this->request->getVar('precio')
            ];
            if ($this->vehiculos->update($idVehiculo, $data) === false) {
                $data['errors'] = $this->vehiculos->errors();
                $data['vehiculo'] = $this->vehiculos->where('id', $idVehiculo)->first();
                $data['active'] = 'vehiculo';
                return view('vehiculos/edit', $data);
            }
            return redirect()->to(base_url('vehiculos'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'VEHÍCULO MODIFICADO',
            ]); 
        }else{
            return view('permisos');
        }
             
    }

    public function delete($idVehiculo)
    {
        if ($this->request->is('delete') && verificar('eliminar vehiculo', $this->session->permisos)) {
           
            $data = $this->vehiculos->update($idVehiculo, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('vehiculos'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'VEHÍCULO DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('vehiculos'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        } else {
            return view('permisos');
        }
    }  
    
}

