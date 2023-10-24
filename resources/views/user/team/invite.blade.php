@component('mail::message')
    # Convite para Participar da Equipe

    Olá, {{ $user->name }},

    Você recebeu um convite para se juntar à equipe {{ $teamName }}!

    **Detalhes do Convite:**
    - Nome da Equipe: {{ $teamName }}
    - E-mail de Convite: {{ $user->email }}

    @if ($defaultPassword)
        - Senha Temporária: {{ $defaultPassword }}
    @endif

@component('mail::button', ['url' => $loginUrl])
        Acessar a Equipe
    @endcomponent

    Atenciosamente,
    {{ $teamName }}
@endcomponent
