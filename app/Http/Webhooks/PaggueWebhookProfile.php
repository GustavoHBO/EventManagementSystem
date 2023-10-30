<?php

namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class PaggueWebhookProfile implements WebhookProfile
{

    public function shouldProcess(Request $request): bool
    {
        return true;
    }
}
