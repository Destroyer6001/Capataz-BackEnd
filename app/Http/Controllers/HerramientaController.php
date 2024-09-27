<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HerramientaController extends Controller
{
    
    public function show()
    {
        $userId = Auth()->id();
        $herramientas = Herramienta::where('User_id',$userId)->get();
        return response()->json($herramientas);
    }

    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50',
            'FechaDeCompra' => 'required|date',
            'Cantidad' => 'required|integer',
            'Fabricante' => 'required|string|max:50'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ]);
        }

        $herramientaCrear = Herramienta::where('User_id',$userId)->where('Nombre',$request->Nombre)->count();

        if($herramientaCrear > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero la herramienta ya se encuentra registrada en el sistema'
            ]);
        }

        $herramienta = new Herramienta();
        $herramienta->Nombre = $request->Nombre;
        $herramienta->Fabricante = $request->Fabricante;
        $herramienta->Cantidad = $request->Cantidad;
        $herramienta->FechaDeCompra = $request->FechaDeCompra;
        $herramienta->User_id = $userId;
        $herramienta->Estado = "Disponible";
        $herramienta->save();

        $data = [
            'Message' => 'Herramienta Creada Correctamente',
            'Herramienta' => $herramienta
        ];

        return response()->json($herramienta);
    }

    
    public function showById($id)
    {
        $herramienta = Herramienta::where('id',$id)->get();
        return response()->json($herramienta);
    }


    public function update(Request $request, $id)
    {
        $userId = Auth()->id();
        $herramienta = Herramienta::find($id);

        $validator = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50',
            'FechaDeCompra' => 'required|date',
            'Cantidad' => 'required|integer',
            'Fabricante' => 'required|string|max:50'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'Errors' => $validator->errors()
            ]);
        }

        $HerramientaEditar = Herramienta::where('id', '!=', $request->id)->where('Nombre',$request->Nombre)
        ->where('User_Id',$userId)->count();

        if($HerramientaEditar > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero no puedes actualizar el nombre de la herramienta por uno ya existente'
            ]); 
        }

        $herramienta->Nombre = $request->Nombre;
        $herramienta->Fabricante = $request->Fabricante;
        $herramienta->Cantidad = $request->Cantidad;
        $herramienta->FechaDeCompra = $request->FechaDeCompra;
        $herramienta->save();

        $data = [
            'Message' => 'Herramienta correctamente actualizada',
            'Herramienta' => $herramienta
        ];

        return response()->json($data);
    }

    
    public function destroy($id)
    {
        $herramienta = Herramienta::find($id);
        $herramienta->delete();
        $data = [
            'Message' => 'Herramienta correctamente eliminada del sistema',
            'Herramienta' => $herramienta
        ];
        return response()->json($data);
    }
}
