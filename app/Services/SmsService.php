<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Member;
use App\Models\SmsLog;

class SmsService
{
    private $sms;

    public function __construct()
    {
        $at = new AfricasTalking(
            config('services.africastalking.username'),
            config('services.africastalking.api_key')
        );
        $this->sms = $at->sms();
    }

    public function envoyerATous(string $message, string $type, ?int $reunionId = null): array
    {
        $membres = Member::where('statut', 'actif')
            ->whereNotNull('telephone')
            ->where('telephone', '!=', '')
            ->get(['telephone', 'prenom', 'nom']);

        if ($membres->isEmpty()) {
            return ['succes' => 0, 'echecs' => 0, 'message' => 'Aucun membre avec numéro de téléphone.'];
        }

        $numeros = $membres->map(fn($m) => $this->formaterNumero($m->telephone))
            ->filter()
            ->values()
            ->toArray();

        $succes = 0;
        $echecs = 0;

        try {
            $result = $this->sms->send([
                'to'      => $numeros,
                'message' => $message,
            ]);

            foreach ($result['data']->SMSMessageData->Recipients as $recipient) {
                if ($recipient->status === 'Success') {
                    $succes++;
                } else {
                    $echecs++;
                }
            }
        } catch (\Exception $e) {
            $echecs = count($numeros);
        }

        SmsLog::create([
            'type'             => $type,
            'message'          => $message,
            'nb_destinataires' => count($numeros),
            'nb_succes'        => $succes,
            'nb_echecs'        => $echecs,
            'envoye_par'       => auth()->id(),
            'reunion_id'       => $reunionId,
        ]);

        return [
            'succes'  => $succes,
            'echecs'  => $echecs,
            'total'   => count($numeros),
        ];
    }

    private function formaterNumero(string $numero): ?string
    {
        $numero = preg_replace('/\s+/', '', $numero);
        if (str_starts_with($numero, '+')) return $numero;
        if (str_starts_with($numero, '00')) return '+' . substr($numero, 2);
        // Sénégal par défaut
        if (strlen($numero) === 9) return '+221' . $numero;
        if (str_starts_with($numero, '221')) return '+' . $numero;
        return null;
    }
}
