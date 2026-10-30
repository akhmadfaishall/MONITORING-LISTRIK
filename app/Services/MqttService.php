<?php

namespace App\Services;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttService
{
    public static function getClient(string $clientId = null): MqttClient
    {
        $server   = env('MQTT_HOST', 'j3a33332.ala.asia-southeast1.emqxsl.com');
        $port     = (int) env('MQTT_PORT', 8883);
        $clientId = $clientId ?? 'laravel_' . uniqid();
        $useTls   = filter_var(env('MQTT_USE_TLS', true), FILTER_VALIDATE_BOOLEAN);

        $client = new MqttClient($server, $port, $clientId);

        $settings = (new ConnectionSettings)
            ->setUsername(env('MQTT_AUTH_USERNAME'))
            ->setPassword(env('MQTT_AUTH_PASSWORD'))
            ->setKeepAliveInterval(60)
            ->setUseTls($useTls)
            ->setTlsVerifyPeer(false); // Untuk kemudahan sertifikat cloud

        $client->connect($settings, true);

        return $client;
    }

    public static function publish(string $topic, array $payload): void
    {
        $client = self::getClient();
        $client->publish($topic, json_encode($payload), 0);
        $client->disconnect();
    }
}