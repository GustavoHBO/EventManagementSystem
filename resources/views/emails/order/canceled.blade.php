@component('mail::message')
    # Pedido Cancelado

    Prezado(a) {{ $user->name }},

    Lamentamos informar que o seu pedido com o número {{ $order->id }} foi cancelado.

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

    **Motivo do Cancelamento:**
    {{ $cancelReason }}

    **Observações:**
    Lamentamos o inconveniente e estamos à disposição para esclarecer quaisquer dúvidas ou problemas. Se desejar, você pode fazer um novo pedido a qualquer momento.

    Agradecemos por considerar nossos serviços.

@endcomponent
