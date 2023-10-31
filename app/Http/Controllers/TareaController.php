<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TareaController extends Controller
{
    public function show()
    {
        $tareas = Tarea::get();
        return response()->json($tareas);
    }
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'Estado' => 'required|string',
                'FechaAsignacion' => 'required|date',
            ]
        );
        if ($validation->fails()) {
            return response->json([
                'Errors'->$validation->errors()
            ], 422);
        }
        $tarea = new Tarea();
        $tarea->Estado = $request->Estado;
        $tarea->FechaAsignacion = $request->FechaAsignacion;
        $tarea->save();
        $data = [
            'Message' => 'Tarea Asignada Correctamente',
            'Tarea' => $tarea
        ];

        return response()->json($data);
    }
    public function showById($id)
    {
        $tarea = Tarea::where('id', $id)->get();
        return response()->json($tarea);
    }
    public function update(Request $request, $id)
    {
        $tarea = Tarea::find($id);
        $validation = Validator::make(
            $request->all(),
            [
                'Estado' => 'required|string',
                'FechaAsignacion' => 'required|date',
            ]
        );
        if ($validation->fails()) {
            return response->json([
                'Errors'->$validation->errors()
            ], 422);
        }
        $tarea->Estado = $request->Estado;
        $tarea->FechaAsignacion = $request->FechaAsignacion;
        $tarea->save();
        $data = [
            'Message' => 'La Asignacion de la Tarea ha sido actualizado correctamente',
            'Tarea' => $tarea
        ];
        return response()->json($data);
    }
    public function destroy($id)
    {
        $tarea = Tarea::find($id);
        $tarea->delete();
        $data = [
            'Message' => 'Tarea Eliminada Correctamente',
            'Tarea' => $tarea
        ];
        return response()->json($data);
    }
}
