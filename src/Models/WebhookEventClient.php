<?php

namespace Terpise\Webhook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEventClient extends Model
{
    use HasFactory;

    protected $table = 'webhook_event_clients';

    protected $guarded = [];

    const STATUS_INIT = 0;
    const STATUS_READ = 1;
    const STATUS_DONE = 2;
    const STATUS_FAIL = 3;

    public function webhookClient()
    {
        return $this->belongsTo(WebhookClient::class, 'webhook_client_id', 'id');
    }

    public function webhookEvent()
    {
        return $this->belongsTo(WebhookEvent::class, 'webhook_event_id', 'id');
    }
}
