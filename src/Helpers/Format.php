<?php
declare(strict_types=1);

final class Format
{
    /** Formatea ID de pedido: 1 → "00001", 42 → "00042" */
    public static function numeroPedido(int $id): string
    {
        return '#' . str_pad((string)$id, 5, '0', STR_PAD_LEFT);
    }

    /** Formatea precio en EUR: 3.5 → "3,50 €" */
    public static function precio(float $valor): string
    {
        return number_format($valor, 2, ',', '.') . ' €';
    }

    /** Formatea fecha MySQL → "19 ene 2026, 14:32" */
    public static function fecha(string $fechaSql): string
    {
        $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
        $ts = strtotime($fechaSql);
        return date('d', $ts) . ' ' . $meses[date('n', $ts) - 1] . ' ' . date('Y, H:i', $ts);
    }

    /** Etiquetas legibles para estados de pedido */
    public static function estadoPedido(string $estado): array
    {
        return match ($estado) {
            'pendiente'  => ['label' => 'Pendiente',  'class' => 'estado--pendiente'],
            'preparando' => ['label' => 'Preparando', 'class' => 'estado--preparando'],
            'listo'      => ['label' => 'Listo',      'class' => 'estado--listo'],
            'entregado'  => ['label' => 'Entregado',  'class' => 'estado--entregado'],
            'cancelado'  => ['label' => 'Cancelado',  'class' => 'estado--cancelado'],
            default      => ['label' => $estado,      'class' => ''],
        };
    }
}
