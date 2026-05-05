<?php

namespace App\Http\Controllers;

use App\Models\Labor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LaborController extends Controller
{
    public function show()
    {
        $userId = Auth()->id();
        $labores = Labor::where('User_id',$userId)->get();
        return response()->json($labores);
    }

    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ]);
        }

        $LaborCrear = Labor::where('Nombre',$request->Nombre)->where('User_id',$userId)->count();

        if($LaborCrear > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero la labor ya se encuentra registrada en el sistema'
            ]);
        }

        $labor = new Labor();
        $labor->Nombre = $request->Nombre;
        $labor->User_id = $userId;
        $labor->save();

        $data = [
            'Message' => 'Labor correctamente registrada',
            'Labor' => $labor
        ];

        return response()->json($data);
    }

  
    public function showById($id)
    {
        $labor = Labor::where('id',$id)->get();
        return response()->json($labor);
    }


    public function update(Request $request, $id)
    {
        $userId = Auth()->id();
        $labor = Labor::find($id);
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:50'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ]);
        }

        $LaborCrear = Labor::where('Nombre',$request->Nombre)->where('id','!=',$labor->id)->where('User_id',$labor->User_id)->count();

        if($LaborCrear > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero la labor ya se encuentra registrada en el sistema'
            ]);
        }

        $labor->Nombre = $request->Nombre;
        $labor->User_id = $userId;
        $labor->save();

        $data = [
            'Message' => 'Labor correctamente actualizada',
            'Labor' => $labor
        ];

        return response()->json($data);
    }

    public function destroy($id)
    {
        $labor = Labor::find($id);
        $labor->delete();
        $data = [
            'Message' => 'Labor eliminada correctamente',
            'Labor' => $labor
        ];

        return response()->json($data);
    }
}
