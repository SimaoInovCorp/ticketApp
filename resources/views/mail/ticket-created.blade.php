<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket criado</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #2563eb; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 13px; opacity: 0.85; }
        .body { padding: 28px 32px; }
        .meta { background: #f8f9fa; border-radius: 6px; padding: 16px; margin-bottom: 20px; font-size: 14px; }
        .meta dt { color: #6b7280; font-weight: 500; margin-bottom: 2px; }
        .meta dd { margin: 0 0 10px; }
        .message-box { border-left: 3px solid #2563eb; padding-left: 16px; font-size: 14px; line-height: 1.6; color: #444; }
        .footer { padding: 16px 32px; background: #f8f9fa; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Novo Ticket: {{ $ticket->subject }}</h1>
        <p>Referência: <strong>{{ $ticket->number }}</strong></p>
    </div>
    <div class="body">
        <p>O seu ticket foi criado com sucesso. A nossa equipa irá responder brevemente.</p>

        <div class="meta">
            <dl>
                <dt>Assunto</dt><dd>{{ $ticket->subject }}</dd>
                <dt>Inbox</dt><dd>{{ $ticket->inbox->name }}</dd>
                @if($ticket->ticketType)
                <dt>Tipo</dt><dd>{{ $ticket->ticketType->name }}</dd>
                @endif
                <dt>Estado</dt><dd>{{ $ticket->status->name }}</dd>
            </dl>
        </div>

        @if($ticket->messages->isNotEmpty())
        <p><strong>Mensagem inicial:</strong></p>
        <div class="message-box">
            {!! $ticket->messages->first()->body !!}
        </div>
        @endif
    </div>
    <div class="footer">
        Este email foi gerado automaticamente. Por favor não responda directamente a este email.
    </div>
</div>
</body>
</html>
