<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoteController extends Controller
{

    public function show()
    {
        $userId = Auth()->id();
        $lotes = Lote::with('Cultivos')->Where('User_id', $userId)->get();
        return response()->json($lotes);

    }

    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),
        [
            'Nombre' => 'required|string|min:4',
            'Tamano' => 'required|integer'
        ]);

        if($validation->fails())
        {
            return response->json([
                'Errors' -> $validation->errors()
            ],422);
        }

        $validarCrear = Lote::where('User_id',$userId)->where('Nombre',$request->Nombre)->count();

        if($validarCrear > 0)
        {
            return response->json([
                'Error' => 'El lote que usted intenta registrar ya se encuentra registrafo en el sistema'
            ],422);
        }
        else
        {
            $lote = new Lote();
            $lote->Nombre = $request->Nombre;
            $lote->Tamano = $request->Tamano;
            $lote->User_id = $userId;
            $lote->Cultivo_id = $request->Cultivo_id;
            $lote->save();
        }

        $data = [
            'Message' => 'Lote Creado Correctamente',
            'Lote' => $lote
        ];

        return response()->json($data);
    }


    public function showById($id)
    {
        $lote = Lote::where('id',$id)->get();
        return response()->json($lote);
    }

    public function update(Request $request, $id)
    {
        $lote = Lote::find($id);

        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|min:4',
            'Tamano' => 'required|integer'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ],422);
        }

        $validarEditar = Lote::where('User_id',$lote->User_id)->where('Nombre',$request->Nombre)->where('id', '!=', $lote->id)->count();

        if($validarEditar > 0)
        {
            return response()->json([
                'Error' => 'El nombre del lote que desea editar ya se encuentra registrado en el sistema'
            ],422);
        }
        else
        {
            $lote->Nombre = $request->Nombre;
            $lote->Tamano = $request->Tamano;
            $lote->Cultivo_id = $request->Cultivo_id;
            $lote->save();
        }

        $data = [
            'Message' => 'El lote ha sido actualizado correctamente',
            'Lote' => $lote
        ];

        return response()->json($data);
    }


    public function destroy($id)
    {
        $lote = Lote::find($id);
        $lote->delete();
        $data = [
            'Message' => 'El lote ha sido eliminado correctamente',
            'Lote' => $lote
        ];

        return response()->json($data);
    }
}
