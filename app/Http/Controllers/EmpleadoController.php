<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate○\Validation\Rule;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
   public function show()
   {
        $id = Auth()->id();
        $empleados = User::where('empleador_id','=',$id)->get();
        return response()->json($empleados);
   }

   public function store(Request $request)
   {
        $user = Auth()->id();
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:100',
            'Documento' => 'required|max:12',
            'Edad' => 'required|integer',
            'Email' => 'required|email',
            'Password' => 'required|min:8'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ]);
        }

        $empleadoCrear = User::where('empleador_id',$user)->where('email',$request->Email)->count();

        if($empleadoCrear > 0)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero el correo ya se encuentra vinculado con otro empleado'
            ]);
        }

        $empleado = new User();
        $empleado->nombre = $request->Nombre;
        $empleado->edad = $request->Edad;
        $empleado->documento = $request->Documento;
        $empleado->email = $request->Email;
        $empleado->empleador_id = $user;
        $empleado->password = Hash::make($request->Password);
        $empleado->save();
        $empleado->assignRole('empleado');


        return response()->json([
            'Message' => 'El empleado ha sido registrado correctamente en el sistema',
            'Empleado' => $empleado,
            'Rol' => $empleado->getRoleNames()->first()
        ]); 
   }

   public function showById($id)
   {
        $empleado = User::where('id',$id)->get();
        return response()->json($empleado);
   }

   public function update(Request $request, $id)
   {
        $empleado = User::find($id);
        $validation = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:100',
            'Documento' => 'required|max:12',
            'Edad' => 'required|integer',
            'Email' => 'required|email'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ]);
        }

        $empleadoEditar = User::where('email',$request->Email)->where('id','!=',$empleado->id)->where('empleador_id',$empleado->empleador_id)->count();

        if($empleadoEditar > 0)
        {
            return response()->json([
                'Error' => 'El email que intenta actualizar ya fue asignado a otro empleado de la finca'
            ]); 
        }

        $empleado->nombre = $request->Nombre; 
        $empleado->edad = $request->Edad; 
        $empleado->documento = $request->Documento;
        $empleado->email = $request->Email;

        if(!Hash::check($request->Password, $empleado->password))
        {
            $empleado->password = Hash::make($request->Password);
        }

        $empleado->save();
        $data = [
            'Message' => 'Empleado actualizado correctamente',
            'Empleado' => $empleado,
            'Rol' => $empleado->getRoleNames()->first()
        ];

        return response()->json($data);
   }

   public function destroy($id)
   {
        $empleado = User::find($id);
        $empleado->removeRole('empleado');
        $empleado->delete();

        $data = [
            'Message' => 'Empleado eliminado correctamente',
            'Empleado' => $empleado
        ];

        return response()->json($data);
   }
}
