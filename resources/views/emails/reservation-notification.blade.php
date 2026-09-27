<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .reservation-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
        }
        .value {
            color: #111827;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'RentACar') }}</h1>
        <h2>{{ $title }}</h2>
    </div>
    
    <div class="content">
        <p>Olá {{ $reservation->client->name ?? 'Cliente' }},</p>
        
        <p>{{ $message }}</p>
        
        <div class="reservation-details">
            <h3 style="margin-top: 0;">Detalhes da Reserva #{{ $reservation->id }}</h3>
            
            <div class="detail-row">
                <span class="label">Veículo:</span>
                <span class="value">{{ $reservation->car->brand ?? '—' }} {{ $reservation->car->model ?? '' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Matrícula:</span>
                <span class="value">{{ $reservation->car->plate_number ?? '—' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Data de Início:</span>
                <span class="value">{{ optional($reservation->start_date)->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Data de Fim:</span>
                <span class="value">{{ optional($reservation->end_date)->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Local de Recolha:</span>
                <span class="value">{{ $reservation->pickupLocation->name ?? '—' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Local de Devolução:</span>
                <span class="value">{{ $reservation->dropoffLocation->name ?? '—' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value">{{ ucfirst($reservation->status) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Valor Total:</span>
                <span class="value" style="font-weight: bold; color: #667eea;">{{ number_format($reservation->total_amount, 2) }} AOA</span>
            </div>
        </div>
        
        <a href="{{ route('client.reservations.show', $reservation) }}" class="button">
            Ver Detalhes da Reserva
        </a>
    </div>
    
    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'RentACar') }}. Todos os direitos reservados.</p>
    </div>
</body>
</html>

