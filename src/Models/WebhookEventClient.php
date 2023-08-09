<?php

namespace Terpise\Webhook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEventClient extends Model
{
    use HasFactory;

    protected $table = 'webhook_event_clients';

    protected $guarded = [];

    public function webhookClient()
    {
        return $this->belongsTo(WebhookClient::class, 'webhook_client_id', 'id');
    }

    public function webhookEvent()
    {
        return $this->belongsTo(WebhookEvent::class, 'webhook_event_id', 'id');
    }
}
