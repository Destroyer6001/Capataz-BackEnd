<?php

namespace App\Http\Controllers;

use App\Models\Prestamos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Herramienta;
class PrestamosController extends Controller
{
    
    public function show()
    {
        $userId = Auth()->id();
        $prestamos = Prestamos::where('User_id',$userId)->join('users','prestamos.Empleado_id','=','users.id')->
        with('Herramienta')->select('prestamos.*', 'users.nombre as nombre_empleado')->get();
        return response()->json($prestamos);
    }

    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),[
            'FechaDePrestamo' => 'date|required',
            'FechaDeEntrega' => 'date|required',
            'Cantidad' => 'integer|required',
            'Empleado_id' => 'required',
            'Herramienta_id' => 'required'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $request->errors()
            ]);
        }

        $herramienta = Herramienta::find($request->Herramienta_id);

        if($herramienta->Cantidad < $request->Cantidad)
        {
            return response()->json([
                'Error' => 'Lo sentimos pero actualmente no contamos con la cantidad necesaria para procesar el prestamo'
            ]);
        }

        $prestamo = new Prestamos();
        $prestamo->FechaDePrestamo = $request->FechaDePrestamo;
        $prestamo->FechaDeEntrega = $request->FechaDeEntrega;
        $prestamo->Cantidad = $request->Cantidad;
        $prestamo->Herramienta_id = $request->Herramienta_id;
        $prestamo->Empleado_id = $request->Empleado_id;
        $prestamo->User_id = $userId;
        $prestamo->Estado = "Activo";
        $prestamo->save();

        $herramienta->Cantidad = $herramienta->Cantidad - $request->Cantidad;
        $herramienta->save();

        $data = [
            'Message' => 'El prestamo ha sido registrado correctamente en el sistema',
            'Prestamo' => $prestamo
        ];

        return response()->json($data);
    }


    public function showById($id)
    {
        $prestamo = Prestamos::where('id',$id)->get();
        return response()->json($prestamo);
    }

    public function update(Request $request,$id)
    {
        $prestamo = Prestamos::find($id);
        $validation = Validator::make($request->all(),
        [
            'FechaDePrestamo' => 'date|required',
            'FechaDeEntrega' => 'date|required',
            'Cantidad' => 'integer|required',
            'Empleado_id' => 'required',
            'Herramienta_id' => 'required'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $request->errors()
            ]);
        }

        if($request->Cantidad != $prestamo->Cantidad && $request->Herramienta_id == $prestamo->Herramienta_id)
        {
            $herramienta = Herramienta::find($request->Herramienta_id);
            $cantidadActual = $herramienta->Cantidad + $prestamo->Cantidad;

            if($cantidadActual < $request->Cantidad)
            {
                return response()->json([
                    'Error' => 'Lo sentimos pero la cantidad del prestamo por la cual desea actualiza excede el stock actual de la herramienta'
                ]);
            }
            else
            {
                $herramienta->Cantidad = $herramienta->Cantidad + $prestamo->Cantidad;
                $herramienta->Cantidad = $herramienta->Cantidad - $request->Cantidad;
                $herramienta->save();
            }
        }

        if( $request->Herramienta_id != $prestamo->Herramienta_id)
        {
            $herramientaAntigua = Herramienta::find($prestamo->Herramienta_id);
            $herramientaNueva = Herramienta::find($request->Herramienta_id);

            if($herramientaNueva->Cantidad < $request->Cantidad)
            {
                return response()->json([
                    'Error' => 'Lo sentimos pero la herramienta por la cual intenta actualizar el prestamo no cuenta con el stock necesario para procesar el prestamo'
                ]);
            }
            else
            {
                $herramientaAntigua->Cantidad = $herramientaAntigua->Cantidad + $prestamo->Cantidad;
                $herramientaAntigua->save();

                $herramientaNueva->Cantidad = $herramientaNueva->Cantidad - $request->Cantidad;
                $herramientaNueva->save();
            }   
        }

        $prestamo->FechaDePrestamo = $request->FechaDePrestamo;
        $prestamo->FechaDeEntrega = $request->FechaDeEntrega;
        $prestamo->Cantidad = $request->Cantidad;
        $prestamo->Empleado_id = $request->Empleado_id;
        $prestamo->Herramienta_id = $request->Herramienta_id;
        $prestamo->save();

        $data = [
            'Message' => 'Actualizacion realizada correctamente',
            'Prestamo' => $prestamo
        ];

        return response()->json($data);
    }

    public function CambioDeEstado ($id)
    {
        $prestamo = Prestamos::find($id);
        $prestamo->Estado = "Entregado";
        $prestamo->save();

        $herramienta = Herramienta::find($prestamo->Herramienta_id);
        $herramienta->Cantidad = $herramienta->Cantidad + $prestamo->Cantidad;
        $herramienta->save();

        $data = [
            'Message' => 'Herramienta entregada correctamente',
            'Herramienta' => $prestamo
        ];

        return response()->json($data);
    }

    public function ShowByEmployee()
    {
        $empleadoId = Auth()->id();
        $prestamos = Prestamos::where('Empleado_id',$empleadoId)->with('Herramienta')->get();
        return response()->json($prestamos);
    }
}
