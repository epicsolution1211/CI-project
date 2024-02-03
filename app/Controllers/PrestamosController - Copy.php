<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\CajasModel;
use App\Models\ClientesModel;
use App\Models\VehiculosModel;
use App\Models\DetPrestamoModel;
use App\Models\PrestamosModel;

// reference the Dompdf namespace
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use DB;

class PrestamosController extends BaseController
{
    private $empresa, $clientes, $vehiculos, $prestamos,
        $detalle, $session, $reglas, $cajas;
    public function __construct()
    {
        helper(['form', 'fecha']);
        $this->empresa = new AdminModel();
        $this->clientes = new ClientesModel();
        $this->vehiculos = new VehiculosModel();
        $this->prestamos = new PrestamosModel();
        $this->detalle = new DetPrestamoModel();
        $this->cajas = new CajasModel();
        $this->session = session();
    }

    public function index()
    {
        if (!verificar('nuevo prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['empresa'] = $this->empresa->first();
        $data['active'] = 'prestamo';
        return view('prestamos/nuevo', $data);
    }

    public function buscarCliente()
    {
        if ($this->request->is('get') && !empty($this->request->getVar('term'))) {
            $data = $this->clientes->like('num_identidad', $this->request->getVar('term'))
                ->where('estado', '1')->findAll(10);
            $result = array();
            foreach ($data as $cliente) {
                $datos['id'] = $cliente['id'];
                $datos['value'] = $cliente['num_identidad'] . ' - ' . $cliente['nombre'] . ' ' . $cliente['apellido'];
                array_push($result, $datos);
            }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function buscarVehiculo()
    {
        if ($this->request->is('get') && !empty($this->request->getVar('term'))) {
            $data = $this->vehiculos->like('vin', $this->request->getVar('term'))
                ->where('estado', '1')->findAll(10);
            $result = array();
            foreach ($data as $vehiculo) {
                $datos['id'] = $vehiculo['id'];
                $datos['value'] = $vehiculo['vin'] . ' - ' . $vehiculo['id_fabricante'] . ' ' . $vehiculo['id_modelo'];
                array_push($result, $datos);
            }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function valorVehiculo()
    {
        if ($this->request->is('get') && !empty($this->request->getVar('term'))) {
            $data = $this->vehiculos->like('vin', $this->request->getVar('term'))
                ->where('estado', '1')->findAll(10);
            $result = array();
            foreach ($data as $vehiculo) {
                $datos['id'] = $vehiculo['id'];
                $datos['value'] = $vehiculo['vin'] . ' - valor: ' . $vehiculo['precio'];
                array_push($result, $datos);
            }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

 

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo prestamo', $this->session->permisos)) {
            //$fecha = date('Y-m-d');
            $fecha = $this->request->getVar('fecha');
            // $count = $this->detalle->where([
            //     'id_prestamo'=>$this->request->getVar('id_cliente'),
            //     'estado'=>1
            // ]);
            // if($count->countAllReaults==0){
                //calcular vencimiento
                if ($this->request->getVar('modalidad') === 'DIARIO') {
                    $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 days'));
                } else if ($this->request->getVar('modalidad') === 'SEMANAL') {
                    $fecha_venc = date('Y-m-d', strtotime($fecha . '+7 days'));
                } else if ($this->request->getVar('modalidad') === 'MENSUAL') {
                    $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 month'));
                } else {
                    $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 year'));
                }
                $data = [
                    'cliente' => $this->request->getVar('cliente'),
                    'vehiculo' => $this->request->getVar('vehiculo'),
                    'valor_vehiculo' => $this->request->getVar('valor_vehiculo'),
                    'importe' => $this->request->getVar('importe_credito'),
                    'enganche' => $this->request->getVar('enganche'),
                    'modalidad' => $this->request->getVar('modalidad'),
                    'tasa_interes' => $this->request->getVar('tasa_interes'),
                    'cuotas' => $this->request->getVar('cuotas'),
                    // 'fecha' => date('Y-m-d H:i:s'),
                    'fecha' => $this->request->getVar('fecha'),
                    'fecha_venc' => $fecha_venc,
                    'id_cliente' => $this->request->getVar('id_cliente'),
                    'id_vehiculo' => $this->request->getVar('id_vehiculo'),
                    'id_usuario' => $this->session->id_usuario, 
                    'saldo_pendiente' => $this->request->getVar('importe_credito'), 
                    'interes_total' => $this->request->getVar('interes_generado')
                ];
                //verificar cliente
                $sqlCliente = $this->prestamos->where([
                    'id_cliente' => $this->request->getVar('id_cliente'),
                    'estado' => '1',
                ])->first();

                //verificar vehiculo
                $sqlVehiculo = $this->prestamos->where([
                    'id_vehiculo' => $this->request->getVar('id_vehiculo'),
                    'estado' => '1',
                ])->first();

                $verificarSaldo = $this->cajas->calcularMovimientos($this->session->id_usuario);
                if ($verificarSaldo['saldo'] >= $this->request->getVar('importe_credito')) {
                    if (empty($sqlCliente) && empty($sqlVehiculo)) {
                        if ($this->prestamos->insert($data) === false) {
                            $data['errors'] = $this->prestamos->errors();
                            $data['empresa'] = $this->empresa->first();
                            $data['modalidad'] = $this->request->getVar('modalidad');
                            $data['cuotas'] = $this->request->getVar('cuotas');
                            $data['active'] = 'prestamo';
                            return view('prestamos/nuevo', $data);
                        }
                        $prestamo = $this->prestamos->getInsertID();
                        if ($prestamo > 0) {
                            //calcular ganancia
                            $ganancia = $this->request->getVar('importe_credito')
                                * ($this->request->getVar('tasa_interes') / 100);
                            //calcular importe cuota
                            $importe_cuota = ($this->request->getVar('importe_credito')
                                / $this->request->getVar('cuotas'))
                                + ($ganancia / $this->request->getVar('cuotas'));

                            for ($i = 1; $i <= $this->request->getVar('cuotas'); $i++) {
                                $presDetalle = $this->detalle->insert([
                                    'cuota' => $i,
                                    'fecha_venc' => $fecha_venc,
                                    'installment'=> ($this->request->getVar('importe_credito') / $this->request->getVar('cuotas')),
                                    'display_installment'=> ($this->request->getVar('importe_credito') / $this->request->getVar('cuotas')),
                                    'interest'=>$this->request->getVar('importe_credito') * ($this->request->getVar('tasa_interes') / 100) / $this->request->getVar('cuotas'),
                                    'importe_cuota' => $importe_cuota,
                                    'id_prestamo' => $prestamo,
                                ]);
                                //consulta de vencimiento
                                $consulta = $this->detalle->where('id', $presDetalle)->first();
                                //calcular vencimiento
                                if ($this->request->getVar('modalidad') === 'DIARIO') {
                                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 days'));
                                } else if ($this->request->getVar('modalidad') === 'SEMANAL') {
                                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+7 days'));
                                } else if ($this->request->getVar('modalidad') === 'MENSUAL') {
                                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 month'));
                                } else {
                                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 year'));
                                }
                            }
                            return redirect()->to(base_url('prestamos/' . $prestamo . '/detail'))->with('respuesta', [
                                'type' => 'success',
                                'msg' => 'PRESTAMO REGISTRADO',
                            ]);
                        } else {
                            return redirect()->to(base_url('prestamos'))->with('respuesta', [
                                'type' => 'warning',
                                'msg' => 'ERROR AL REALIZAR PRESTAMO',
                            ]);
                        }
                    } else {
                        return redirect()->to(base_url('prestamos'))->with('respuesta', [
                            'type' => 'warning',
                            'msg' => 'YA TIENES UN PRESTAMO PENDIENTE O EL VEHÍCULO ESTA EN FINANCIAMIENTO',
                        ]);
                    }
                } else {
                    return redirect()->to(base_url('prestamos'))->with('respuesta', [
                        'type' => 'warning',
                        'msg' => 'SALDO INSUFICIENTE',
                    ]);
                }
            // }else {
            //         return redirect()->to(base_url('prestamos'))->with('respuesta', [
            //             'type' => 'warning',
            //             'msg' => 'ERROR AL REALIZAR PRESTAMO',
            //         ]);
            // }
        }else{
            return view('permisos');
        }
    }

    public function detail($id)
    {
        if (!verificar('ver prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, v.vin, v.id_fabricante, v.id_modelo, v.precio, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('vehiculos AS v', 'prestamos.id_vehiculo = v.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $detaildata = $this->detalle->where('id_prestamo', $id)->first();
        $count_now = $this->detalle
            ->where([
                'cuota >=' => $detaildata['cuota'],
                'id_prestamo' => $id,
                'estado' => 1
            ])
            ->countAllResults();
            
            if($count_now==0){
                
                $data['outstanding_balance_without']=0;
                $data['outstanding_balance_with']=0;
                $data['outstanding_balance_without_pay']=0;
            
            }else{
                $data['outstanding_balance_without'] = 
                $this->detalle->selectSum('installment')->where(['id_prestamo' => $id])->first()['installment']
                -$this->detalle->selectSum('paid_installment')->where(['id_prestamo' => $id])->first()['paid_installment']
                + $this->detalle->selectSum('interest')->where(['paid_installment >'=> 0])->first()['interest'];

                $data['outstanding_balance_with'] = $this->detalle->selectSum('installment')->where(['id_prestamo' => $id])->first()['installment']
                - $this->detalle->selectSum('paid_installment')->where(['id_prestamo' => $id])->first()['paid_installment']
                + $this->detalle->selectSum('interest')->where(['id_prestamo' => $id])->first()['interest'];
                    
                $data['outstanding_balance_without_pay'] = 
                (float)($this->detalle->selectSum('installment')->where(['id_prestamo' => $id])->first()['installment'] 
                -$this->detalle->selectSum('paid_installment')->where(['id_prestamo' => $id])->first()['paid_installment'])
                + (float)($this->detalle->where(['id_prestamo'=> $id])->first()['interest'])
                +(float)($this->detalle->selectSum('interest')->where(['paid_installment !=' => '0','id_prestamo'=> $id])->first()['interest']);
            }

        $data['paid_interest'] = $this->detalle->selectSum('interest')->where(['estado' => '0','id_prestamo' => $id])->first();

        $detail = $this->detalle->where([
                    'estado' => '1',
                    'id_prestamo' => $id
                ])->first();
        $data['next_pay'] = $detail ? $detail['installment']-$detail['paid_installment'] : 0;
        $data['next_pay_with'] = $detail ? $detail['installment']+$detail['interest'] -$detail['paid_installment'] : 0;
        
        $data['active'] = 'prestamo';
        return view('prestamos/detail', $data);
    }

    public function reporte($id)
    {
        if (!verificar('ver prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, c.direccion, v.vin, v.id_fabricante, v.id_modelo, v.precio, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('vehiculos AS v', 'prestamos.id_vehiculo = v.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $data['empresa'] = $this->empresa->first();
    
        
        $detaildata = $this->detalle->where('id_prestamo', $id)->first();
        $count_now = $this->detalle
            ->where([
                'cuota >=' => $detaildata['cuota'],
                'id_prestamo' => $id,
                'estado' => 1
            ])
            ->countAllResults();
            
        if($count_now==0){
            $data['outstanding_balance_without']=0;
            $data['outstanding_balance_with']=0;
        }else{

            $data['outstanding_balance_without'] = $this->detalle->selectSum('installment')->where(['id_prestamo' => $id])->first()['installment']
            -$this->detalle->selectSum('paid_installment')->where(['id_prestamo' => $id])->first()['paid_installment']
            + $this->detalle->selectSum('interest')->where(['paid_installment >'=> 0])->first()['interest'];

            $data['outstanding_balance_with'] = $this->detalle->selectSum('installment')->where(['id_prestamo' => $id])->first()['installment']
            -$this->detalle->selectSum('paid_installment')->where(['id_prestamo' => $id])->first()['paid_installment']
            +$this->detalle->selectSum('interest')->where(['id_prestamo' => $id])->first()['interest'];
        }

        $data['paid_interest'] = $this->detalle->selectSum('interest')->where([
            'estado' => '0',
            'id_prestamo' => $id
        ])->first();

        $detail = $this->detalle->where([
            'estado' => '1',
            'id_prestamo' => $id
        ])->first();
        $data['next_pay'] = $detail ? $detail['installment']+$detail['interest'] -$detail['paid_installment'] : 0;
        
        
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start();
        echo view('prestamos/contrato', $data);
        $html = ob_get_clean();

        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'vertical');
        $this->response->setHeader('Content-Type', 'application/pdf');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', ['Attachment' => false]);
    }

    public function update($id)
    {
        if ($this->request->is('put') && verificar('abono prestamo', $this->session->permisos)) {
            $consulta = $this->detalle
                ->select('detalle_prestamos.id_prestamo as id_prestamo, detalle_prestamos.fecha_venc, p.modalidad, detalle_prestamos.cuota as cuota')
                ->join('prestamos AS p', 'detalle_prestamos.id_prestamo = p.id')
                ->where('detalle_prestamos.id', $id)->first();
            $count_prev = $this->detalle
                ->where([
                    'cuota <' => $consulta['cuota'],
                    'id_prestamo' => $consulta['id_prestamo'],
                    'estado' => 1
                ])
                ->countAllResults();
            
            if(($count_prev)>0){
                return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'Pago previo a la deuda.',
                ]);
            }
            else {
                $data = $this->detalle->update($id, ['estado' => '0']);
            }

            if ($data) {
                //calcular vencimiento
                if ($consulta['modalidad'] === 'DIARIO') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 days'));
                } else if ($consulta['modalidad'] === 'SEMANAL') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+7 days'));
                } else if ($consulta['modalidad'] === 'MENSUAL') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 month'));
                } else {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 year'));
                }
                //comprobar las cuotas
                $datos = $this->detalle->where([
                    'id_prestamo' => $consulta['id_prestamo'],
                    'estado' => '1'
                ])->first();
                if (!empty($datos)) {
                    $this->prestamos->update($consulta['id_prestamo'], ['fecha_venc' => $fecha_venc]);
                    return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'ESTADO CAMBIADO',
                    ]);
                } else {
                    $this->prestamos->update($consulta['id_prestamo'], ['estado' => '2']);
                    return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'PRESTAMO FINALIZADO',
                    ]);
                }
            } else {
                return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL CAMBIAR EL ESTADO',
                ]);
            }
        }else{
            return view('permisos');
        }
    }
    public function updateinstallment()
    {
        if ($this->request->is('POST')) {
            $id_prestamo = $this->request->getVar('id_prestamo');
            $custompayamount = $this->request->getVar('customize');
            
            $index = $this->detalle->where('estado', 1)->first();
            $id = $index['id'];
            $quota=$index['importe_cuota']-$index['interest'];

            $current_amount = $index['installment'];
            $current_interest = $index['interest'];
            $current_paid= $index['paid_installment'];
            $display_installment= $index['display_installment'];
            
            // $current_interest = $index['interest'];

            
            $allsub_installment = $this->detalle->selectSum('installment')->where([
                'id_prestamo' => $id_prestamo,
                'estado' => 1
            ])->first()['installment'];
            
            $count_now = $this->detalle
            ->where([
                'cuota >=' => $index['cuota'],
                'id_prestamo' => $id_prestamo,
                'estado' => 1
            ])
            ->countAllResults();
            
            if($count_now==0){
                
                return redirect()->to(base_url('prestamos/' . $id_prestamo . '/detail'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => ' Ya haz liquidado el total del importe.',
                ]);
            } else{
            
                if($custompayamount+$current_paid<$current_amount+$current_interest){
                    $now = $this->detalle
                    ->where([
                        'estado'=> 1, 
                        'id'=>$id])
                    ->set([
                        'display_installment' => $custompayamount+$current_paid,
                        'paid_installment' => $custompayamount+$current_paid
                        ])
                    ->update();    
                }
                else if($custompayamount+$current_paid == $current_amount+$current_interest){
                    $now = $this->detalle
                    ->where([
                        'estado'=> 1, 
                        'id'=>$id
                    ])
                    ->set([
                        'estado'=> 0,
                        'display_installment'=>$current_amount+$current_interest,
                        'paid_installment'=>$current_amount+$current_interest
                        ])
                    ->update();

                    $count_now = $this->detalle
                        ->where([
                            'id_prestamo' => $id_prestamo,
                            'estado' => 1
                        ])
                        ->countAllResults();

                    if($count_now==0){
                        $this->prestamos->where(['id'=>$id_prestamo])->set(['estado'=>2])->update();
                    }
                }
                else if($custompayamount+$current_paid > $current_amount+$current_interest){

                    // if($custompayamount+$current_paid<$current_amount+$current_interest+$index['importe_cuota']){

                    //     $reset_installment = $this->detalle
                    //     ->where([
                    //         'estado'=> 1, 
                    //         'id'=>$id
                    //     ])
                    //     ->set([
                    //         'installment'=> $custompayamount+$current_paid-$current_interest,
                    //         'display_installment'=>$custompayamount+$current_paid,
                    //         'paid_installment'=>$custompayamount+$current_paid,
                    //         'estado'=> 0
                    //     ])->update();

                    //     $reset_installment = $this->detalle
                    //     ->where([
                    //         'estado'=> 1, 
                    //         'id'=>$id+1
                    //     ])
                    //     ->set(
                    //         'installment','installment - ' . ($custompayamount + $current_paid - $current_amount - $current_interest), false
                    //     )->set(
                    //         'display_installment','display_installment - ' . ($custompayamount + $current_paid - $current_amount - $current_interest), false
                    //     )->update();

                    // }
                    
                    // else if($custompayamount+$current_paid == $current_amount+$current_interest+$index['importe_cuota']){
                    //     $now = $this->detalle
                    //     ->where([
                    //         'estado'=> 1, 
                    //         'id'=>$id
                    //     ])
                    //     ->set([
                    //         'estado'=> 0,
                    //         'installment'=>$custompayamount+$current_paid,
                    //         'display_installment'=>$custompayamount+$current_paid,
                    //         'paid_installment'=>$custompayamount+$current_paid
                    //         ])
                    //     ->update();

                    //     $now = $this->detalle
                    //     ->where([
                    //         'estado'=> 1, 
                    //         'id'=>$id+1
                    //     ])
                    //     ->set([
                    //         'estado'=> 0,
                    //         'display_installment'=>0,
                    //         'paid_installment'=>0,
                    //         'installment'=>0
                    //         ])
                    //     ->update();
                    //     $count_now = $this->detalle
                    //     ->where([
                    //         'cuota >=' => $index['cuota'],
                    //         'id_prestamo' => $id_prestamo,
                    //         'estado' => 1
                    //     ])
                    //     ->countAllResults();
                    //     if($count_now==0){
                    //         $this->prestamos->where(['id'=>$id_prestamo])->set(['estado'=>1])->update();
                    //     }
                        
                    // }
                    
                    // if($custompayamount+$current_paid > $current_amount+$current_interest+$index['importe_cuota']){
                        if($custompayamount+$current_paid>=$allsub_installment+$current_interest){
                    
                            $now = $this->detalle
                            ->where(['id'=>$id])
                            ->set([
                                'estado'=> 0, 
                                'installment' => $allsub_installment-$current_interest,
                                'display_installment' => $custompayamount+$current_paid,
                                'paid_installment' => $custompayamount+$current_paid,
                            ])
                            ->update();

                            $reset_installment = $this->detalle->where([
                                'cuota >' => $index['cuota'],
                                'id_prestamo' => $id_prestamo,
                                'estado' => 1
                            ])->set([
                                'installment'=> 0,
                                'interest'=>0,
                                'estado'=> 0,
                                'display_installment' => 0,
                                'paid_installment' => 0,
                            ])->update();

                            $count_now = $this->detalle
                                ->where([
                                    'cuota >=' => $index['cuota'],
                                    'id_prestamo' => $id_prestamo,
                                    'estado' => 1
                                ])
                                ->countAllResults();
                            if($count_now==0){
                                $this->prestamos->where(['id'=>$id_prestamo])->set(['estado'=>2])->update();
                            }
                        }
                        else {
                            $paid_count=(int)((
                                $custompayamount
                                +$current_paid
                                -$current_amount
                                -$current_interest)/$index['importe_cuota']);

                            $dif=$custompayamount+$current_paid-$current_amount-$current_interest-$index['importe_cuota']*$paid_count;
                            
                            $now = $this->detalle
                                ->where(['id'=>$id])
                                ->set([
                                    'estado'=> 0, 
                                    'installment' => $custompayamount+$current_paid-$current_interest,
                                    'display_installment' => $custompayamount+$current_paid,
                                    'paid_installment' => $custompayamount+$current_paid,
                                ])
                                ->update();

                            $now = $this->detalle
                                ->where(['id'=>$id+$paid_count+1])
                                ->set(
                                    'installment', 'installment -' . $dif+$current_interest, false
                                )->set(
                                    'display_installment', 'display_installment -' . $dif+$current_interest, false
                                )
                                ->update();
                            
                            for($i=1;$i<=$paid_count; $i++){

                                $reset_installment = $this->detalle->where([
                                    'cuota' => $index['cuota']+$i,
                                    'id_prestamo' => $id_prestamo,
                                    
                                ])->set([
                                    'estado' => 0,
                                    'installment'=>0,
                                    'display_installment'=> 0,
                                ])->update();
                            }
                        }
                    // }
                }    
            }  
            return redirect()->back();
        }
    }
    public function enviarCorreo()
    {
        $this->reglas = [
            'correo' => [
                'rules' => 'required|valid_email'
            ],
            'mensaje' => [
                'rules' => 'required'
            ]
        ];
        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $correo = $this->request->getVar('correo');
            $mail = new PHPMailer(true);
            try {
                $empresa = $this->empresa->first();
                $cliente = $this->clientes->where('correo', $correo)->first();
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'lovenaju2@gmail.com';                     //SMTP username
                $mail->Password   = 'xgrcrehtwxmrhmdf';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($empresa['correo'], $empresa['nombre']);
                $mail->addAddress($correo, $cliente['nombre']);

                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Contrato de prestamo - ' . $empresa['nombre'];
                $mail->Body    = $this->request->getVar('mensaje');
                $mail->send();
                return redirect()->to(base_url('prestamos/' . $this->request->getVar('id_prestamo') . '/detail'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CORREO ENVIADO',
                ]);
            } catch (Exception $e) {
                return redirect()->to(base_url('prestamos/' . $this->request->getVar('id_prestamo') . '/detail'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo,
                ]);
            }
        } else {
            $data['validator'] = $this->validator;

            $data['prestamo'] = $this->prestamos
                ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
                ->join('clientes AS c', 'prestamos.id_cliente = c.id')
                ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
                ->where('prestamos.id', $this->request->getVar('id_prestamo'))->first();

            $data['detalles'] = $this->detalle->where('id_prestamo', $this->request->getVar('id_prestamo'))->findAll();
            $data['active'] = 'prestamo';
            return view('prestamos/detail', $data);
        }
    }

    public function historial()
    {
        if (!verificar('historial prestamos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'prestamo';
        return view('prestamos/historial', $data);
    }

    public function listHistorial()
    {
        if ($this->request->is('get')) {
            $data = $this->prestamos->select('prestamos.*, c.identidad, c.num_identidad, c.nombre, c.apellido, v.vin, v.id_fabricante, v.id_modelo, v.precio, u.nombre AS usuario')
                //->from('prestamos AS p', true)
                ->join('clientes AS c', 'prestamos.id_cliente = c.id')
                ->join('vehiculos AS v', 'prestamos.id_vehiculo = v.id')
                ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
                ->where('prestamos.estado != 0')->findAll();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['vencimiento'] = fechaPerzo($data[$i]['fecha_venc']);
                
                $importe_cuota = $this->detalle->selectSum('paid_installment')->where([
                    'estado' => '0',
                    'id_prestamo' => $data[$i]['id']
                ])->first()['paid_installment']+$this->detalle->selectSum('interest')->where([
                    'paid_installment >'=>0,
                    'id_prestamo' => $data[$i]['id']
                ])->first()['interest'];

                $data[$i]['import_cuota']=$importe_cuota;
                $data[$i]['interest']=$data[$i]['importe']-$this->detalle->selectSum('interest')->where(['estado' => '0','id_prestamo' => $data[$i]['id']])->first()['interest'];
                
                $detaildata = $this->detalle->where('id_prestamo', $data[$i]['id'])->first();
                $count_now = $this->detalle
                    ->where([
                        'cuota >=' => $detaildata['cuota'],
                        'id_prestamo' => $data[$i]['id'],
                        'estado' => 1
                    ])
                    ->countAllResults();
                    
                if($count_now==0){
                    $outstanding_valance=0;
                }else{
                $outstanding_valance = $this->detalle->selectSum('installment')->where([
                    'id_prestamo' => $data[$i]['id']
                ])->first()['installment']-$this->detalle->selectSum('paid_installment')->where([
                    'id_prestamo' => $data[$i]['id']
                ])->first()['paid_installment'] + $this->detalle->selectSum('interest')->where([
                    'estado' => '1',
                    'id_prestamo' => $data[$i]['id']
                ])->first()['interest'];
                }
                // $data[$i]['import_cuota']=$ganancia;
                $data[$i]['outstanding_valance']=number_format($outstanding_valance,2);

                $count=$this->detalle->where(['estado'=>1, 'id_prestamo'=>$data[$i]['id']])->countAllResults();
                if($count==0){
                    $data[$i]['ganancia']=0;
                    // $data[$i]['gd']=0;
                }else{
                    $data[$i]['ganancia'] = ($importe_cuota != null) ? number_format($importe_cuota - 
                                                                                    $data[$i]['importe']-
                                                                                    $this->detalle->selectSum('interest')
                                                                                    ->where(['estado' => '0','id_prestamo' => $data[$i]['id']])->first()['interest'], 2) : 
                                                                                    '-' . number_format($data[$i]['importe'] + 
                                                                                                        $this->detalle->selectSum('interest')
                                                                                                        ->where(['estado' => '0','id_prestamo' => $data[$i]['id']])->first()['interest'], 2);
                }
                $data[$i]['gd'] = ($importe_cuota != null) ? $importe_cuota - 
                                                            $data[$i]['importe'] - 
                                                            $this->detalle->selectSum('interest')
                                                            ->where(['estado' => '0','id_prestamo' => $data[$i]['id']])->first()['interest'] : 
                                                            '-' . $data[$i]['importe'] - 
                                                            $this->detalle->selectSum('interest')
                                                            ->where(['estado' => '0','id_prestamo' => $data[$i]['id']])->first()['interest'];

            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function delete($id)
    {
        if ($this->request->is('delete') && verificar('eliminar prestamo', $this->session->permisos)) {
            //$data = $this->prestamos->delete($id);
            $data = $this->prestamos->update($id, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'PRESTAMO ELIMINADO',
                ]);
            } else {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        }else{
            return view('permisos');
        }
    }
}
