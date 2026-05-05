<?php

namespace App\Http\Controllers;

use App\Models\Tareas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TareasController extends Controller
{
    public function show()
    {
        $userId = Auth()->id();
        $tareas = Tareas::join('users','tareas.Empleado_id','=','users.id')->with('Labor','Lote')
        ->where('User_id',$userId)->select('tareas.*', 'users.nombre as nombre_empleado')->get();
        return response()->json($tareas);
    }


    public function store(Request $request)
    {
        $userId = Auth()->id();
        $validation = Validator::make($request->all(),[
            'FechaDeAsignacion' => 'required|date',
            'Lote_id' => 'required',
            'Labor_id' => 'required',
            'Empleado_id' => 'required'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $request->errors()
            ]);
        }

        $tarea = new Tareas();
        $tarea->FechaDeAsignacion = $request->FechaDeAsignacion;
        $tarea->Lote_id = $request->Lote_id;
        $tarea->Labor_id = $request->Labor_id;
        $tarea->Empleado_id = $request->Empleado_id;
        $tarea->Estado = "En ejecucion";
        $tarea->User_id = $userId;
        $tarea->save();

        $data = [
            'Message' => 'Tarea asignada correctamente',
            'Tarea' => $tarea
        ];

        return response()->json($data);
    }

    
    public function showById($id)
    {
        $tarea = Tareas::where('id',$id)->get();
        return response()->json($tarea);
    }

    
    public function update(Request $request, $id)
    {
        $tarea = Tareas::find($id);
        
        $validation = Validator::make($request->all(),[
            'FechaDeAsignacion' => 'required|date',
            'Lote_id' => 'required',
            'Labor_id' => 'required',
            'Empleado_id' => 'required'
        ]);

        if($validation->fails())
        {
            return response()->json([
                'Errors' => $request->errors()
            ]);
        }

        $tarea->FechaDeAsignacion = $request->FechaDeAsignacion;
        $tarea->Lote_id = $request->Lote_id;
        $tarea->Labor_id = $request->Labor_id;
        $tarea->Empleado_id = $request->Empleado_id;
        $tarea->save();

        $data = [
            'Message' => 'Datos de la tarea actualizados correctamente',
            'Tarea' => $tarea
        ];

        return response()->json($data);
    }


    public function CambioDeEstado ($id)
    {
        $tarea = Tareas::find($id);
        $tarea->Estado = "Tarea Realizada";
        $tarea->save();

        $data = [
            'Message' => 'Estado de la tarea correctamente actualizado',
            'Tarea' => $tarea
        ];

        return response()->json($data);
    }

    public function ShowByEmployee()
    {
        $EmpladoId = Auth()->id();
        $tareas = Tareas::where('Empleado_id',$EmpladoId)->with('Lote','Labor')->get();
        return response()->json($tareas);
    }
}
