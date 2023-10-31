<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CultivoController extends Controller
{

    public function show()
    {
        $user_Id = Auth()->id();
        $cultivos = Cultivo::where('User_Id',$user_Id)->get();
        return response()->json($cultivos);
    }

    public function store(Request $request)
    {
        $user_Id = Auth()->id();
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|min:4'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ],422);
        }

        $existeCultivo = Cultivo::where('User_id',"=",$user_Id)->where('Nombre',"=",$request->Nombre)->count();

        if($existeCultivo > 0)
        {
            return response()->json([
                'Error' => 'El cultivo que intenta registrar ya se encuentra registrado en el sistema'
            ],422);
        }
        else
        {
            $cultivo = new Cultivo();
            $cultivo->Nombre = $request->Nombre;
            $cultivo->User_id = $user_Id;
            $cultivo->save();
        }

        $data = [
            'Message' => 'Cultivo Registrado Exitosamente En El Sistema',
            'Cultivo' => $cultivo
        ];

        return response()->json($cultivo);

    }


    public function showById($id)
    {
        $cultivo = Cultivo::where('Id',$id)->get();
        return response()->json($cultivo);
    }


    public function update(Request $request, $id)
    {
        $cultivo = Cultivo::find($id);
        $user_Id = Auth()->id();

        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|min:4'
        ]);

        if($validation->fails())
        {
            return response->json([
                'Errors' => $validation->errors()
            ],422);
        }

        $existeEditar = Cultivo::where('User_id',$cultivo->User_id)->where('Nombre',$request->Nombre)->where('id', '!=',$cultivo->id)->count();

        if($existeEditar > 0)
        {
            return response()->json(
                [
                    'Error' => 'El Nombre que intenta actualizar ya esta registrado en el sistema'
                ]
            ,422);
        }
        else
        {
            $cultivo->Nombre = $request->Nombre;
            $cultivo->save();
        }

        $data = [
            'Message' => 'Cultivo actualizado correctamente',
            'Cultivo' => $cultivo
        ];

        return response()->json($data);
    }

    public function destroy($id)
    {
        $cultivo = Cultivo::find($id);
        $cultivo->delete();

        $data = [
            'Message' => 'Cultivo Eliminado Correctamente',
            'Cultivo' => $cultivo
        ];

        return response()->json($data);
    }
}
