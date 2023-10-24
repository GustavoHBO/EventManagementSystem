<?php

namespace App\Models;

use App\Http\Providers\PaggueServiceProvider;
use App\Interfaces\PaymentInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class PaymentPIX implements PaymentInterface
{

    private Payment $payment; // Payment data.

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Make the payment.
     * @return array|bool|string
     */
    public function pay(): array|bool|string
    {
        $paymentData = PaggueServiceProvider::billinOrder($this->payment);
        $paymentData['qrCode'] = $this->generateQrCode($paymentData['payment']);
        return $paymentData;
    }

    public function generateQrCode(string $qrCodeData): string
    {
        $renderer = new ImageRenderer(new RendererStyle(300), new ImagickImageBackEnd());
        $writer = new Writer($renderer);
        return base64_encode($writer->writeString($qrCodeData));
    }
}
