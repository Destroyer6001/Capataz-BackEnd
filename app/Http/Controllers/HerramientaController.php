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
        $herramientas = Herramienta::get();
        return response()->json($herramientas);
    }
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'Nombre' => 'required|string',
                'Cantidad' => 'required|integer',
                'FechaCompra' => 'required|date',
                'Fabricante' => 'required|string',
            ]
        );
        if ($validation->fails()) {
            return response->json([
                'Errors'->$validation->errors()
            ], 422);
        }
        $herramienta = new Herramienta();
        $herramienta->Nombre = $request->Nombre;
        $herramienta->Cantidad = $request->Cantidad;
        $herramienta->FechaCompra = $request->FechaCompra;
        $herramienta->Fabricante = $request->Fabricante;
        $herramienta->save();
        $data = [
            'Message' => 'Herramienta Registrada Correctamente',
            'Herramienta' => $herramienta
        ];
        return response()->json($data);
    }
    public function showById($id)
    {
        $herramienta = Herramienta::where('id', $id)->get();
        return response()->json($herramienta);
    }
    public function update(Request $request, $id)
    {
        $herramienta = Herramienta::find($id);
        $validation = Validator::make(
            $request->all(),
            [
                'Nombre' => 'required|string',
                'Cantidad' => 'required|integer',
                'FechaCompra' => 'required|date',
                'Fabricante' => 'required|string',
            ]
        );
        if ($validation->fails()) {
            return response->json([
                'Errors'->$validation->errors()
            ], 422);
        }
        $herramienta->Nombre = $request->Nombre;
        $herramienta->Cantidad = $request->Cantidad;
        $herramienta->FechaCompra = $request->FechaCompra;
        $herramienta->Fabricante = $request->Fabricante;
        $data = [
            'Message' => 'La Herramienta ha sido actualizada correctamente',
            'Herramienta' => $herramienta
        ];
        return response()->json($data);
    }
    public function destroy($id)
    {
        $herramienta = herramienta::find($id);
        $herramienta->delete();
        $data = [
            'Message' => 'Herramienta Eliminada Correctamente',
            'Herramienta' => $herramienta
        ];
        return response()->json($data);
    }
}
