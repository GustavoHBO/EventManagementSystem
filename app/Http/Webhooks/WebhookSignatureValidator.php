<?php

namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class WebhookSignatureValidator implements SignatureValidator
{
    /**
     * Return true if the given request is allowed to enter the application.
     * @param  Request  $request  - Request data.
     * @param  WebhookConfig  $config  - Webhook configuration.
     * @return bool - True if the request is valid.
     */
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        /**
         * \* Pega a assinatura que vem no header Signature
         * \*/
        $signature = $request->header('Signature');
        if (!$signature) {
            return false;
        }

        $signingSecret = env('PAGGUE_CLIENT_TOKEN');
        /**
         * \* Nesse ponto você vai criar do seu lado um hash de assinatura
         * \* e utilizar ele para comparar com o hash assinado enviado na request
         * \* Se as chaves $signature e $computedSignature
         * \*
         * \* O getContent inclui todo o conteudo da request, nao apenas o body
         * \*/
        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);
        /**
         * \* Verificar se as duas chaves são iguais
         * \* Se forem iguais significa que não houve nenhuma alteração na requisição e o Body é confiável
         * \* @return bool Returns TRUE quando as duas strings são iguais.
         * \*/
        return hash_equals($signature, $computedSignature);
    }
}
