<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova resposta</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #2563eb; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 13px; opacity: 0.85; }
        .body { padding: 28px 32px; }
        .message-box { border-left: 3px solid #2563eb; padding-left: 16px; font-size: 14px; line-height: 1.6; color: #444; }
        .attachments { margin-top: 16px; font-size: 13px; }
        .attachments a { color: #2563eb; }
        .footer { padding: 16px 32px; background: #f8f9fa; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Nova resposta no seu ticket</h1>
        <p>Referência: <strong>{{ $ticket->number }}</strong> — {{ $ticket->subject }}</p>
    </div>
    <div class="body">
        <p>Recebeu uma nova resposta de <strong>{{ $ticketMessage->author->name ?? 'Sistema' }}</strong>:</p>

        <div class="message-box">
            {!! $ticketMessage->body !!}
        </div>

        @if($ticketMessage->attachments->isNotEmpty())
        <div class="attachments">
            <p><strong>Anexos:</strong></p>
            <ul>
                @foreach($ticketMessage->attachments as $attachment)
                <li><a href="{{ $attachment->url }}">{{ $attachment->original_name }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="footer">
        Este email foi gerado automaticamente. Por favor não responda directamente a este email.
    </div>
</div>
</body>
</html>
