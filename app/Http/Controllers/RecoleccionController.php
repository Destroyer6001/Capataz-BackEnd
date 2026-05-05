<?php

namespace App\Http\Controllers;

use App\Models\Recoleccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RecoleccionController extends Controller
{    public function show()
    {
        $userId = Auth()->id();
        $registros = Recoleccion::where('User_id',$userId)->join('users','recoleccions.Empleado_id',"=","users.id")
        ->select('recoleccions.*', 'users.nombre as nombre_empleado')->get();
        return response()->json($registros);
    }

    
    
    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),[
            'Fecha' => 'required|date',
            'Cantidad' => 'required|integer',
            'Precio' => 'required|integer',
            'Empleado_id' => 'required'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $request->errors()
            ]);
        }

        $registro = new Recoleccion();
        $registro->Fecha = $request->Fecha;
        $registro->Cantidad = $request->Cantidad;
        $registro->Precio = $request->Precio;
        $registro->User_id = $userId;
        $registro->Empleado_id = $request->Empleado_id;
        $registro->Total = $request->Precio * $request->Cantidad;
        $registro->save();

        $data = [
            'Message' => 'Registro de recoleccion correctamente registrado en el sistema',
            'Registro' => $registro
        ];

        return response()->json($data);
    }

   
    public function showById($id)
    {
        $registro = Recoleccion::where('id',$id)->get();
        return response()->json($registro);
    }

    public function ShowByEmployee()
    {
        $empleadoId = Auth()->id();
        $registros = Recoleccion::where('Empleado_id',$empleadoId)->get();
        return response()->json($registros);
    }
}
