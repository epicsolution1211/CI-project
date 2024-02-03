<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelosModel;


class ModelosController extends BaseController
{
    private $modelos, $session;

    public function __construct()
    {
        $this->modelos = new ModelosModel();
        helper(['form']);
        $this->session = session();
    }


    /* F A R B R I C A N T E S */
    public function index()
    {
        if (!verificar('listar modelos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('modelos/index', $data);
    }

    public function listar()
    {
        $data = $this->modelos->where('estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo modelo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'vehiculo';
        return view('modelos/new', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo modelo', $this->session->permisos)){
            $data = [
                'id_modelo' => $this->request->getVar('id_modelo'),
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'nombre_modelo' => $this->request->getVar('nombre_modelo')
            ];
            if ($this->modelos->insert($data) === false) {
                $data['errors'] = $this->modelos->errors();
                $data['active'] = 'vehiculo';
                return view('modelos/new', $data);
            }
            return redirect()->to(base_url('modelos'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'MODELO REGISTRADO',
            ]);
        }else{
            return view('permisos');
        }
              
    }
    
    public function edit($idModelo){
        if (!verificar('editar modelo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['modelo'] = $this->modelos->where('id', $idModelo)->first();
        $data['active'] = 'vehiculo';
        return view('modelos/edit', $data);
    }

    public function update($idModelo)
    {
        if ($this->request->is('put') && verificar('editar modelo', $this->session->permisos)){
            $data = [
                'id_modelo' => $this->request->getVar('id_modelo'),
                'id_fabricante' => $this->request->getVar('id_fabricante'),
                'nombre_modelo' => $this->request->getVar('nombre_modelo')
            ];
            if ($this->modelos->update($idModelo, $data) === false) {
                $data['errors'] = $this->modelos->errors();
                $data['modelos'] = $this->modelos->where('id', $idModelo)->first();
                $data['active'] = 'vehiculo';
                return view('modelos/edit', $data);
            }
            return redirect()->to(base_url('modelos'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'MODELO MODIFICADO',
            ]); 
        }else{
            return view('permisos');
        }
             
    }

    public function delete($idModelo)
    {
        if ($this->request->is('delete') && verificar('eliminar modelo', $this->session->permisos)) {
           
            $data = $this->modelos->update($idModelo, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('modelos'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'MODELO DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('modelos'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        } else {
            return view('permisos');
        }
    }

}


