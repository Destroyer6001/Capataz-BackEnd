<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate○\Validation\Rule;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Register(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'direccion' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'municipio' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'propietario' => 'required|string|max:150'
        ]);

        if($validation->fails())
        {
            return response()->json(['Errors' => $validation->errors()],422);
        }

        if($request->password == $request->confirmpassword)
        {
            $user = new User();
            $user->direccion = $request->direccion;
            $user->email = $request->email;
            $user->municipio = $request->municipio;
            $user->departamento = $request->departamento;
            $user->propietario = $request->propietario;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->assignRole('user');

            $data = [
                'Message' => 'Usuario creado correctamente',
                'User' => $user,
                'Token' => $user->createToken('API TOKEN')->plainTextToken,
                'Role' => $user->getRoleNames()->first()
            ];

            return response()->json($data);
        }
        else
        {
            return response()->json(['Error' => 'Las contraseñas no coinciden'],422);
        }
    }

    public function Login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'email' => 'required|email|string:50',
            'password' => 'required|min:8'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ],422);
        }

        if(!Auth::attempt($request->only('email','password')))
        {
            return response()->json([
                'Errors' => ['Unathorized']
            ],401);
        }

        $user = User::Where('email',$request->email)->first();

        $data = [
            'Message' => 'Usuario logueado correctamente',
            'User' => $user,
            'Token' => $user->createToken('API TOKEN')->plainTextToken,
            'Role' => $user->getRoleNames()->first()
        ];

        return response()->json($data);
    }


    public function Logout()
    {
        auth()->user()->tokens()->delete();
        $data = [
            'Message' => 'Usuario deslogueado correctamente'
        ];

        return response()->json($data);
    }


    public function Edit(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'direccion' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'municipio' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'propietario' => 'required|string|max:150'
        ]);

        $user = User::find($id);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $validation->errors()
            ],422);
        }

        $user->propietario = $request->propietario;
        $user->email = $request->email;
        $user->municipio = $request->municipio;
        $user->departamento = $request->departamento;
        $user->direccion = $request->direccion;

        if($request->password != null)
        {
            if($request->password != $user->password)
            {
                if($request->password != $request->confirmpassword)
                {
                    return response()->json([
                        'Error' => 'Las contraseñas no coinciden'
                    ],422);
                }
                else
                {
                    $user->password = Hash::make($request->password);
                    $user->save();
                }
            }
            else
            {
                return response()->json([
                    'Error' => 'La contraseña nueva es igual a la contreseña anterior'
                ],422);
            }
        }
        else
        {
            $user->save();
        }

        $data = [
            'Message' => 'Actualizacion exitosa',
            'User' => $user,
            'Token' => $user->createToken("API TOKEN")->plainTextToken,
            'Role' => $user->getRoleNames()->first()
        ];

        return response()->json($data);

    }
}
