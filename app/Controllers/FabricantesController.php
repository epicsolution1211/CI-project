<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FabricantesModel;


class FabricantesController extends BaseController
{
    private $fabricantes, $session;

    public function __construct()
    {
        $this->fabricantes = new FabricantesModel();
        helper(['form']);
        $this->session = session();
    }


    /* F A R B R I C A N T E S */
    public function index()
    {
        if (!verificar('listar fabricantes', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('fabricantes/index', $data);
    }

    public function listar()
    {
        $data = $this->fabricantes->where('estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo fabricante', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('fabricantes/new', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo fabricante', $this->session->permisos)){
            $data = [
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'nombre_fabricante' => $this->request->getVar('nombre_fabricante')
            ];
            if ($this->fabricantes->insert($data) === false) {
                $data['errors'] = $this->fabricantes->errors();
                $data['active'] = 'vehiculo';
                return view('fabricantes/new', $data);
            }
            return redirect()->to(base_url('fabricantes'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'FABRICANTE REGISTRADO',
            ]);
        }else{
            return view('permisos');
        }
              
    }
    
    public function edit($idFabricante){
        if (!verificar('editar fabricante', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['fabricante'] = $this->fabricantes->where('id', $idFabricante)->first();
        $data['active'] = 'vehiculo';
        return view('fabricantes/edit', $data);
    }

    public function update($idFabricante)
    {
        if ($this->request->is('put') && verificar('editar fabricante', $this->session->permisos)){
            $data = [
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'nombre_fabricante' => $this->request->getVar('nombre_fabricante'),
            ];
            if ($this->fabricantes->update($idFabricante, $data) === false) {
                $data['errors'] = $this->fabricantes->errors();
                $data['fabricantes'] = $this->fabricantes->where('id', $idFabricante)->first();
                $data['active'] = 'vehiculo';
                return view('fabricantes/edit', $data);
            }
            return redirect()->to(base_url('fabricantes'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'FABRICANTE MODIFICADO',
            ]); 
        }else{
            return view('permisos');
        }
             
    }

    public function delete($idFabricante)
    {
        if ($this->request->is('delete') && verificar('eliminar fabricante', $this->session->permisos)) {
           
            $data = $this->fabricantes->update($idFabricante, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('fabricantes'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'FABRICANTE DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('fabricantes'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        } else {
            return view('permisos');
        }
    }

}
