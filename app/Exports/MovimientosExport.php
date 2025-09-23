<?php

namespace App\Exports;

use App\Models\Movimientos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class MovimientosExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $predioId;
    protected $startDate;
    protected $endDate;

    public function __construct($predioId, $startDate, $endDate)
    {
        $this->predioId = $predioId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Devuelve una colección de los datos a exportar.
     */
    public function collection()
    {
        return Movimientos::with(['usuario', 'predio', 'planCuenta'])
            ->where('id_predio', $this->predioId)
            ->whereBetween('fecha', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($movimiento) {
                return [
                    'Predio' => '  ' . ($movimiento->predio->nombre_predio ?? 'N/A') . '  ',
                    'Nombre' => '  ' . ($movimiento->planCuenta->nomcta ?? 'N/A') . '  ',
                    'Cantidad' => '  ' . $movimiento->cantidad . '  ',
                    'Fecha' => '  ' . $movimiento->fecha . '  ',
                    'Descripción' => '  ' . $movimiento->descripcion . '  ',
                ];
            });
    }

    /**
     * Definir los encabezados para el archivo de Excel.
     */
    public function headings(): array
    {
        return [
            'Predio',
            'Nombre',
            'Cantidad',
            'Fecha',
            'Descripción',
        ];
    }

    /**
     * Definir los anchos de las columnas.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 40,
        ];
    }
}
