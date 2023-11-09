<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Productos;

class MovimientosController extends Controller
{

    public function showByProducto($id)
    {
        $movimientos = Movimientos::where('Producto_id', $id)->get();
        return response()->json($movimientos);   
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'Producto' => 'required|integer',
            'TipoDeMovimiento' => 'required',
            'Cantidad' => 'required|integer'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'Errors' => $validator->errors()
            ]);
        }

        if($request->TipoDeMovimiento == "Ingreso")
        {
            $producto = Productos::find($request->Producto);
            $fechadehoy = Carbon::now();
            $fechadevencimiento = Carbon::parse($producto->FechaDeVencimiento);

            if($fechadehoy > $fechadevencimiento)
            {
                return response()->json(
                    [
                        'Error' => 'Lo sentimos pero no se puede ingresar existencias a un producto vencido'
                    ]
                );
            }

            $productoCantidad = $producto->Cantidad;
            $productoCantidadActualizada = $productoCantidad + $request->Cantidad;
            $producto->Cantidad = $productoCantidadActualizada;
            $producto->save();

            $movimiento = new Movimientos();
            $movimiento->Cantidad = $request->Cantidad;
            $movimiento->Fecha = $fechadehoy;
            $movimiento->TipoDeMovimiento = $request->TipoDeMovimiento;
            $movimiento->Producto_id = $request->Producto;
            $movimiento->save();

        }else
        {
            $producto = Productos::find($request->Producto);
            $fechadehoy = Carbon::now();

            if($producto->Cantidad < $request->Cantidad)
            {
                return response()->json([
                    'Error' => 'Lo sentimo el producto no cuenta con la cantidad necesaria de existencias para poder procesar el movimiento'
                ]);
            }

            $productoCantidad = $producto->Cantidad;
            $productoCantidadActualizada = $productoCantidad - $request->Cantidad;
            $producto->Cantidad = $productoCantidadActualizada;
            $producto->save();

            $movimiento = new Movimientos();
            $movimiento->Cantidad = $request->Cantidad;
            $movimiento->Fecha = $fechadehoy;
            $movimiento->TipoDeMovimiento = $request->TipoDeMovimiento;
            $movimiento->Producto_id = $request->Producto;
            $movimiento->save();
        }


        $data = [
            'Message' => 'Movimiento Registrado Correctamente',
            'Movimiento' => $movimiento
        ];

        return response()->json($data);
    }

    
}
