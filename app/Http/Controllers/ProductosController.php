<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProductosController extends Controller
{
    
    public function show()
    {
        $User_id = Auth()->id();
        $Productos = Productos::where('User_id', $User_id)->get();
        return response()->json($Productos);
    }

    public function store(Request $request)
    {
        $User_id = Auth()->id();
        $validator = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50',
            'Distribuidor' => 'required|string|max:50',
            'Lote' => 'required|string|max:11',
            'Cantidad' => 'required|integer',
            'FechaDeCompra' => 'required|date',
            'FechaDeVencimiento' => 'required|date'
        ]);


        if($validator->fails())
        {
            return response()->json([
                'Errors' => $validator->errors()
            ],422);
        }

        $fechadehoy = Carbon::now();
        $fechadevencimiento = Carbon::parse($request->FechaDeVencimiento);
        $fechadecompra = Carbon::parse($request->FechaDeCompra);


        if($fechadevencimiento->lessThan($fechadehoy))
        {
            return response()->json([
                'Error' => 'Lo sentimos pero no se puede registrar un producto que ya se encuentra vencido'
            ],422);
        }

        $loteProductoCrear = Productos::where('Lote',$request->Lote)->where('User_id',$User_id)->count();

        if($loteProductoCrear > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero el producto que desea ingresar ya se encuentra registrado en el sistema'
            ],422);
        }

        $producto = new Productos();
        $producto->Nombre = $request->Nombre;
        $producto->Distribuidor = $request->Distribuidor;
        $producto->Lote = $request->Lote;
        $producto->Cantidad = $request->Cantidad;
        $producto->FechaDeCompra = $fechadecompra;
        $producto->FechaDeVencimiento = $fechadevencimiento;
        $producto->Estado = true;
        $producto->User_id = $User_id;
        $producto->save();

        $data = [
            'Message' => 'Producto Creado Correctamente',
            'Producto' => $producto
        ];

        return response()->json($data);
    }

    
    public function showById($id)
    {
        $producto = Productos::where('id',$id)->get();
        return response()->json($producto);
    }

    
    public function update(Request $request, $id)
    {
        $producto = Productos::find($id);

        $validator = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50',
            'Distribuidor' => 'required|string|max:50',
            'Lote' => 'required|string|max:11',
            'FechaDeVencimiento' => 'required|date',
            'FechaDeCompra' => 'required|date'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'Errors' => $validator->errors()
            ],422);
        }


        $ProductoValidarEditar = Productos::where('id', '!=', $id)->where('Lote',$request->Lote)
        ->where('User_id',$producto->User_id)->count();

        if($ProductoValidarEditar > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero el lote ya se encuentra asociado a otro producto'
            ],422);
        }

        $fechadehoy = Carbon::now();
        $fechadevencimiento = Carbon::parse($request->FechaDeVencimiento);
        $fechadecompra = Carbon::parse($request->FechaDeCompra);
        $fechadevencimientoAnterior = Carbon::parse($producto->FechaDeVencimiento);

        if($fechadevencimiento !== $fechadevencimientoAnterior)
        {
            if($fechadevencimiento->lessThan($fechadehoy))
            {
                return response()->json([
                    'Error' => 'Lo sentimos pero la fecha de vencimiento que intenta actualizar es menor a la fecha actual'
                ],422);
            }
        }

        $producto->Nombre = $request->Nombre;
        $producto->Distribuidor = $request->Distribuidor; 
        $producto->Lote = $request->Lote;
        $producto->FechaDeCompra = $fechadecompra;
        $producto->FechaDeVencimiento = $fechadevencimiento;
        $producto->save();

        $data = [
            'Message' => 'El producto ha sido correctamente actualizado',
            'Producto' => $producto
        ];

        return response()->json($data);
    }

   
    public function dissable($id)
    {
        $producto = Productos::find($id);
        $producto->Estado = False;
        $producto->save();

        $data = [
            'Message' => 'Producto inhabilitado',
            'Producto' => $producto
        ];

        return response()->json($data);
    }
}
