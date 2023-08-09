<?php

namespace Terpise\Webhook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookClient extends Model
{
    use HasFactory;

    protected $table = 'webhook_clients';
    protected $guarded = [];
}
