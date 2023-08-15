<?php

namespace Terpise\Webhook;

use Terpise\Webhook\Jobs\WebhookReadEvent;
use Terpise\Webhook\Models\WebhookClient;
use Terpise\Webhook\Models\WebhookEvent;
use Terpise\Webhook\Models\WebhookEventClient;
use Illuminate\Support\Facades\Http;

class WebhookService
{
    public static function event($data)
    {
        $webhookEvent = WebhookEvent::create([
            'data' => json_encode($data),
        ]);
        $webhookClientSubscribes = WebhookClient::where("subscribe", 1)->get();

        $items = [];
        foreach ($webhookClientSubscribes as $webhookClientSubscribe) {
            $items[] = [
                'webhook_event_id' => $webhookEvent->id,
                'webhook_client_id' => $webhookClientSubscribe->id,
            ];
        }
        WebhookEventClient::insert($items);
        WebhookReadEvent::dispatch();
    }

    public static function hasEvent()
    {
        try {
            return WebhookEventClient::where('status', WebhookEventClient::STATUS_INIT)->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function readEvents()
    {
        try {
            $query = WebhookEventClient::query()
                ->where('status', WebhookEventClient::STATUS_INIT)
                ->orderBy('id')
                ->select(['id'])
                ->limit(100);
            $rows = $query->get();
            if (empty($rows)) {
                return [];
            }
            $query->update([
                'status' => WebhookEventClient::STATUS_READ,
            ]);
            return $rows;
        } catch (\Exception $e) {
            return [];
        }
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
        ])->post($webhookClient->callback_url, json_decode($webhookEvent->data));

        if ($response->successful()) {
            $webhookEventClient->update([
                'status' => 2,
            ]);
        } else {
            $webhookEventClient->update([
                'status' => 3,
            ]);
        }
    }
}