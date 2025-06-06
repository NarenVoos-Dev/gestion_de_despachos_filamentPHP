<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .filters { margin-bottom: 15px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="date">Generado: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <div class="filters">
        <strong>Filtros aplicados:</strong><br>
        Fecha: {{ $filtros['fecha_inicio'] ? Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') : 'N/A' }} 
        al {{ $filtros['fecha_fin'] ? Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') : 'N/A' }}<br>
        Estados: {{ $filtros['estados'] ? implode(', ', $filtros['estados']) : 'Todos' }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Total</th>
                <th>Valor Total (COP)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->producto_nombre }}</td>
                <td>{{ number_format($item->total_cantidad, 0) }}</td>
                <td>${{ number_format($item->total_valor, 2) }}</td>
            </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td>TOTALES</td>
                <td>{{ number_format($data->sum('total_cantidad'), 0) }}</td>
                <td>${{ number_format($data->sum('total_valor'), 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        {{ config('app.name') }} - Página {PAGENO} de {nbpg}
    </div>
</body>
</html>