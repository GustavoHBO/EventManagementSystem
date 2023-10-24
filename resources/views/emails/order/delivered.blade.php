@component('mail::message')
    # Pagamento Confirmado

    Prezado(a) {{ $user->name }},

    O pagamento do seu pedido foi confirmado com sucesso!

    Número do Pedido: {{ $order->id }}
    Data da Compra: {{ $order->created_at->format('d/m/Y H:i') }}

    **Detalhes do Pedido:**
@component('mail::table')
        | Produto | Quantidade | Preço |
        |:-------------------- |:----------:| -------------:|
        @foreach ($order->orderItems as $orderItem)
            @foreach ($orderItem->tickets as $ticket)
                | {{ $ticket->ticketPrice->lot->event->name }} ({{ $ticket->id }}) | 1x | R$ {{ number_format($ticket->ticketPrice->price, 2) }} |
            @endforeach
        @endforeach
        | **Total**            |            | **R$ {{ number_format($order->total_amount, 2) }}** |
    @endcomponent

    **Observações:**
    Obrigado por escolher nossos serviços.

    **Importante:**
    Você pode acompanhar os detalhes do seu pedido em sua conta. Em caso de dúvidas ou problemas, não hesite em nos contatar.

    Agradecemos por sua preferência!

@endcomponent
