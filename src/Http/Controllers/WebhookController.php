<?php

namespace Terpise\Webhook\Http\Controllers;

use Terpise\Webhook\WebhookClientRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    protected WebhookClientRepository $clientRepository;

    public function __construct(WebhookClientRepository $clientRepository,)
    {
        $this->clientRepository = $clientRepository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
            'callback_url' => 'required',
            'verify_token' => 'required',
        ]);
        if (!$this->clientRepository
            ->exists($request->get('client_id'), $request->get('client_secret'))) {
            return new Response(['message' => 'Client does not exist'], 404);
        }
        if ($this->clientRepository
            ->verifyCallback($request->get('verify_token'), $request->get('callback_url'))) {
            $result = $this->clientRepository
                ->subscribe(
                    $request->get('client_id'),
                    $request->get('verify_token'),
                    $request->get('callback_url')
                );
            return new Response($result->only(['id', 'subscribe']));
        }
        return new Response([
            'message' => 'Verify callback failed',
        ], 404);
    }

    public function show(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);
        $client = $this->clientRepository->get($request->get('client_id'), $request->get('client_secret'));
        if (empty($client)) {
            return new Response(['message' => 'Not found'], 404);
        }
        return new Response([
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'callback_url' => $client->callback_url,
            'subscribe' => $client->subscribe,
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);
        $client = $this->clientRepository->unsubscribe($request->get('client_id'), $request->get('client_secret'));
        if (empty($client)) {
            return new Response(['message' => 'Not found'], 404);
        }
        return new Response(['message' => 'Success']);
    }
}
