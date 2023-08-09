<?php

namespace Terpise\Webhook;

use Terpise\Webhook\Models\WebhookClient;
use Terpise\Webhook\Models\WebhookEvent;
use Terpise\Webhook\Models\WebhookEventClient;
use Illuminate\Support\Facades\Http;

class WebhookService
{
    public static function event($data, $type)
    {
        $webhookEvent = WebhookEvent::create([
            'data' => json_encode($data),
            'type' => $type
        ]);;
        $webhookClientSubscribes = WebhookClient::where("subscribe", 1)->get();

        $items = [];
        foreach ($webhookClientSubscribes as $webhookClientSubscribe) {
            $items[] = [
                'webhook_event_id' => $webhookEvent->id,
                'webhook_client_id' => $webhookClientSubscribe->id,
            ];
        }
        WebhookEventClient::insert($items);
    }

    public static function events($limit = null)
    {
        if (empty($limit)) {
            $limit = config('webhook.limit', 30);
        }
        return WebhookEventClient::query()
            ->where('status', 0)
            ->orderBy('id')
            ->select(['id'])
            ->limit($limit)
            ->get();

    }

    public static function sendEvent($id)
    {
        $webhookEventClient = WebhookEventClient::find($id);
        if (empty($webhookEventClient)) {
            return;
        }
        $webhookClient = $webhookEventClient->webhookClient;
        $webhookEvent = $webhookEventClient->webhookEvent;

        $response = Http::withHeaders([
            'verity-token' => $webhookClient->verify_token,
        ])->post($webhookClient->callback_url, [
            'event' => $webhookEvent->type,
            'data' => $webhookEvent->data,
        ]);

        if ($response->successful()) {
            $webhookEventClient->update([
                'status' => 1,
            ]);
        } else {
            $webhookEventClient->update([
                'status' => 2,
            ]);
        }
    }
}