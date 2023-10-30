@component('mail::message')
    # Confirmação de Pedido

    Prezado(a) {{ $user->name }},

    Seu pedido foi realizado com sucesso!

    Número do Pedido: {{ $order->id }}
    Data da Compra: {{ $order->created_at->format('d/m/Y H:i') }}

    **Detalhes do Pedido:**
@component('mail::table')
        | Produto | Quantidade | Preço |
        |:---------------------|:----------:| -----------:|
        @foreach ($order->orderItems as $orderItem)
            @foreach ($orderItem->tickets as $ticket)
                | {{ $ticket->ticketPrice->lot->event->name }} ({{ $ticket->id }}) | 1x | R$ {{ number_format($ticket->ticketPrice->price, 2) }} |
            @endforeach
        @endforeach
        | **Total** | | **R$ {{ number_format($order->total_amount, 2) }}** |
    @endcomponent

    **Observações:**
    Obrigado por escolher nossos serviços.

    **Importante:**
    Você tem 10 minutos para concluir o pagamento deste pedido. Após esse período, o pedido poderá ser cancelado.

    **Utilize o QRCode para realizar o pagamento:**
@component('mail::footer')
    <img src="data:image/png;base64, {{ $order->paymentData['qrCode'] }}" alt="QR Code">
    @endcomponent
@endcomponent
