<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\XenditWebhookController;

Route::post('xendit/webhook', [XenditWebhookController::class, 'handle']);
