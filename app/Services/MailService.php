<?php

namespace App\Services;

class MailService
{
    public static function send(string $to, string $subject, string $html): bool
    {
        $apiKey = env('RESEND_API_KEY');
        $fromEmail = env('RESEND_FROM_EMAIL');
        $fromName = env('MAIL_FROM_NAME', 'SGI ATLAS');

        if (empty($apiKey) || empty($fromEmail)) {
            error_log('Resend API key or from email not configured.');
            return false;
        }

        $url = 'https://api.resend.com/emails';
        
        $data = [
            'from' => "{$fromName} <{$fromEmail}>",
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log('Failed to send email via Resend API: ' . $response);
        return false;
    }
}
