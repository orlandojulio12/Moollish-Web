<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaInsumo;
use App\Models\TipoUsoInsumo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriaInsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $categorias = CategoriaInsumo::withCount('insumos')
            ->orderBy('nombre')
            ->get();

        return view('inventario_animales.categorias_insumos', compact('user', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('inventario_animales.crear_categoria_insumo', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias_insumos,nombre',
            'descripcion' => 'nullable|string',
            'tipos_uso' => 'nullable|array',
            'tipos_uso.*.nombre' => 'required|string|max:100',
            'tipos_uso.*.descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $categoria = new CategoriaInsumo();
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;
            $categoria->save();

            // Crear los tipos de uso para esta categoría
            if ($request->has('tipos_uso')) {
                foreach ($request->tipos_uso as $tipoUso) {
                    if (!empty($tipoUso['nombre'])) {
                        $tipo = new TipoUsoInsumo();
                        $tipo->nombre = $tipoUso['nombre'];
                        $tipo->descripcion = $tipoUso['descripcion'] ?? null;
                        $tipo->categoria_insumo_id = $categoria->id;
                        $tipo->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría de insumos creada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la categoría: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $categoria = CategoriaInsumo::with(['tiposUsos', 'insumos'])
            ->findOrFail($id);

        return view('inventario_animales.ver_categoria_insumo', compact('user', 'categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $categoria = CategoriaInsumo::with('tiposUsos')
            ->findOrFail($id);

        return view('inventario_animales.editar_categoria_insumo', compact('user', 'categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:categorias_insumos,nombre,' . $id,
            'descripcion' => 'nullable|string',
            'tipos_uso_actuales' => 'nullable|array',
            'tipos_uso_actuales.*.id' => 'required|exists:tipos_usos_insumos,id',
            'tipos_uso_actuales.*.nombre' => 'required|string|max:100',
            'tipos_uso_actuales.*.descripcion' => 'nullable|string',
            'tipos_uso_nuevos' => 'nullable|array',
            'tipos_uso_nuevos.*.nombre' => 'required|string|max:100',
            'tipos_uso_nuevos.*.descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $categoria = CategoriaInsumo::findOrFail($id);
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;
            $categoria->save();

            // Actualizar tipos de uso existentes
            if ($request->has('tipos_uso_actuales')) {
                foreach ($request->tipos_uso_actuales as $tipoUsoData) {
                    if (isset($tipoUsoData['id'])) {
                        $tipoUso = TipoUsoInsumo::findOrFail($tipoUsoData['id']);
                        $tipoUso->nombre = $tipoUsoData['nombre'];
                        $tipoUso->descripcion = $tipoUsoData['descripcion'] ?? null;
                        $tipoUso->save();
                    }
                }
            }

            // Crear nuevos tipos de uso
            if ($request->has('tipos_uso_nuevos')) {
                foreach ($request->tipos_uso_nuevos as $tipoUsoNuevo) {
                    if (!empty($tipoUsoNuevo['nombre'])) {
                        $tipoUso = new TipoUsoInsumo();
                        $tipoUso->nombre = $tipoUsoNuevo['nombre'];
                        $tipoUso->descripcion = $tipoUsoNuevo['descripcion'] ?? null;
                        $tipoUso->categoria_insumo_id = $categoria->id;
                        $tipoUso->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('categorias.show', $categoria->id)
                ->with('success', 'Categoría de insumos actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la categoría: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $categoria = CategoriaInsumo::withCount('insumos')->findOrFail($id);

            // Verificar si hay insumos asociados
            if ($categoria->insumos_count > 0) {
                return back()->with('error', 'No se puede eliminar la categoría porque tiene insumos asociados.');
            }

            // Eliminar los tipos de uso asociados
            TipoUsoInsumo::where('categoria_insumo_id', $id)->delete();

            // Eliminar la categoría
            $categoria->delete();

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría de insumos eliminada correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un tipo de uso
     */
    public function eliminarTipoUso($id)
    {
        try {
            $tipoUso = TipoUsoInsumo::withCount('usos')->findOrFail($id);
            $categoriaId = $tipoUso->categoria_insumo_id;

            // Verificar si hay usos asociados
            if ($tipoUso->usos_count > 0) {
                return back()->with('error', 'No se puede eliminar el tipo de uso porque está siendo utilizado.');
            }

            // Eliminar el tipo de uso
            $tipoUso->delete();

            return redirect()->route('categorias.edit', $categoriaId)
                ->with('success', 'Tipo de uso eliminado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el tipo de uso: ' . $e->getMessage());
        }
    }
}
