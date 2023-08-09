<?php

namespace Terpise\Webhook;

use Terpise\Webhook\Models\WebhookClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebhookClientRepository
{
    public function getId()
    {
        return $this->getNextAutoIncrementId((new WebhookClient())->getTable());
    }

    public function getSecret()
    {
        return Str::random(40);
    }

    public function getNextAutoIncrementId($tableName)
    {
        $query = "SHOW TABLE STATUS LIKE '{$tableName}'";

        $result = DB::select($query);

        // The 'Auto_increment' field holds the next auto-increment ID value
        if (!empty($result) && isset($result[0]->Auto_increment)) {
            return $result[0]->Auto_increment;
        }

        // Handle error or table not found case
        return null;
    }

    public function idExists($id)
    {
        return WebhookClient::where('id', $id)->exists();
    }

    public function store($name, $secret = null, $id = null)
    {
        $params = [
            "name" => $name,
            "secret" => is_null($secret) ? $this->getSecret() : $secret,
        ];
        if ($id) {
            $params["id"] = $id;
        }
        return WebhookClient::create($params);
    }

    public function exists($clientId, $clientSecret)
    {
        return WebhookClient::query()
            ->where('id', $clientId)
            ->where('secret', $clientSecret)
            ->exists();
    }

    public function verifyCallback($verifyToken, $callback)
    {
        try {
            $challenge = Str::random(40);
            $response = Http::withHeaders([
                'verity-token' => $verifyToken,
            ])->get($callback, [
                'challenge' => $challenge,
            ]);
            if ($response->successful()) {
                return json_decode($response->body())->challenge == $challenge;
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function subscribe($clientId, $verifyToken, $callback)
    {
        return WebhookClient::updateOrCreate(["id" => $clientId], [
            "verify_token" => $verifyToken,
            "callback_url" => $callback,
            "subscribe" => 1,
        ]);
    }

    public function get($clientId, $clientSecret)
    {
        return WebhookClient::where('id', $clientId)
            ->where('secret', $clientSecret)
            ->first();
    }

    public function unsubscribe($clientId, $clientSecret)
    {
        return WebhookClient::where("id", $clientId)->where('secret', $clientSecret)->update([
            "verify_token" => null,
            "callback_url" => null,
            "subscribe" => 0,
        ]);
    }
}