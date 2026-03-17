<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GananciaPesoExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    protected array $rows;
    protected array $headers;
    protected string $modo;

    public function __construct(array $data)
    {
        $this->modo = $data['modo'] ?? 'lote';
        $this->rows = [];
        $this->headers = [];

        if ($this->modo === 'individual') {
            $this->buildIndividual($data);
        } else {
            $this->buildLote($data);
        }
    }

    private function buildIndividual(array $data): void
    {
        $animal = $data['animal'] ?? [];
        $codigo = $animal['codigo'] ?? '-';
        $nombre = $animal['nombre'] ?? '-';
        $raza   = $animal['raza']   ?? '-';
        $lote   = $animal['lote']   ?? '-';

        $this->headers = ['Código', 'Nombre', 'Raza', 'Lote', 'Fecha', 'Período', 'Peso (kg)', 'Ganancia (kg)', 'Días entre pesajes', 'Ganancia diaria (kg/día)'];

        foreach ($data['data'] ?? [] as $item) {
            $this->rows[] = [
                $codigo,
                $nombre,
                $raza,
                $lote,
                $item['fecha_pesaje'],
                $item['periodo'],
                $item['peso'],
                $item['ganancia_kg'] ?? '-',
                $item['dias_entre_pesajes'] ?? '-',
                $item['ganancia_diaria_kg'] ?? '-',
            ];
        }
    }

    private function buildLote(array $data): void
    {
        $this->headers = ['Lote', 'Período', 'Código', 'Nombre', 'Peso Inicio (kg)', 'Peso Cierre (kg)', 'Ganancia (kg)'];

        foreach ($data['data'] ?? [] as $lote) {
            foreach ($lote['meses'] ?? [] as $mes) {
                foreach ($mes['animales'] ?? [] as $animal) {
                    $this->rows[] = [
                        $lote['lote_nombre'],
                        $mes['periodo'],
                        $animal['codigo'],
                        $animal['nombre'] ?? '-',
                        $animal['peso_inicio'],
                        $animal['peso_cierre'],
                        $animal['ganancia_kg'],
                    ];
                }
            }
        }
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE49B39']],
            ],
        ];
    }

    public function title(): string
    {
        return $this->modo === 'individual' ? 'Ganancia Individual' : 'Ganancia por Lote';
    }
}
