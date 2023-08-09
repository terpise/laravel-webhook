<?php
use Illuminate\Support\Facades\Route;
use Terpise\Webhook\Http\Controllers\WebhookController;

Route::get('subscribe', [WebhookController::class, 'show']);
Route::post('subscribe', [WebhookController::class, 'store']);
Route::post('unsubscribe', [WebhookController::class, 'unsubscribe']);