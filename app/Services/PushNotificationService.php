<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;


class PushNotificationService
{
    public function send(
        User $user,
        string $title,
        string $message,
        string $type = 'umum',
        ?string $url = null,
        ?string $referensiTipe = null,
        ?int $referensiId = null
    ): void {
        // Simpan notifikasi ke database
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $title,
            'pesan' => $message,
            'tipe' => $type,
            'referensi_tipe' => $referensiTipe,
            'referensi_id' => $referensiId,
            'dibaca' => false,
        ]);

        // Ambil semua subscription milik user
        $subscriptions = PushSubscription::where(
            'user_id',
            $user->id
        )->get();

        if ($subscriptions->isEmpty()) {
            Log::warning('Push gagal: subscription tidak ditemukan.', [
                'user_id' => $user->id,
            ]);

            return;
        }

        // Konfigurasi Web Push
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.subject'),
                'publicKey' => config('webpush.public_key'),
                'privateKey' => config('webpush.private_key'),
            ],
        ]);

        // Masukkan semua subscription ke queue
        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'keys' => [
                        'p256dh' => $subscription->public_key,
                        'auth' => $subscription->auth_token,
                    ],
                    'contentEncoding' => $subscription->content_encoding
                        ?: 'aes128gcm',
                ]),
                json_encode([
                    'title' => $title,
                    'body' => $message,
                    'url' => $url,
                    'type' => $type,
                ])
            );
        }

        // Kirim push
        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                Log::info('Push berhasil dikirim.', [
                    'user_id' => $user->id,
                    'title' => $title,
                    'endpoint' => $report->getEndpoint(),
                ]);
            } else {
                Log::error('Push gagal dikirim.', [
                    'user_id' => $user->id,
                    'title' => $title,
                    'endpoint' => $report->getEndpoint(),
                    'reason' => $report->getReason(),
                    'response' => $report->getResponse()
                        ? $report->getResponse()->getBody()->getContents()
                        : null,
                ]);
            }
        }
    }

    
}