/*
  =============================================================================
  PROYEK PTI 2026 - MONITORING LISTRIK RUMAH
  FIRMWARE ESP32 #1 : MONITORING ENERGI LISTRIK (PZEM-004T V3.0)
  =============================================================================
  Topic MQTT Publish : kelompok20/pzem/data
  Broker             : EMQX Cloud (j3a33332.ala.asia-southeast1.emqxsl.com)
  Port               : 8883 (TLS/SSL)
  =============================================================================
*/

#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <PubSubClient.h>
#include <PZEM004Tv30.h>
#include <ArduinoJson.h>

// ===== 1. KONFIGURASI WI-FI & MQTT =====
const char* ssid          = "NAMA_WIFI_ANDA";       // Ganti dengan SSID Wi-Fi Anda
const char* password      = "PASSWORD_WIFI_ANDA";   // Ganti dengan Password Wi-Fi
const char* mqtt_server   = "j3a33332.ala.asia-southeast1.emqxsl.com";
const int   mqtt_port     = 8883;
const char* mqtt_user     = "kelompok20";            // Kredensial EMQX yang sudah dibuat
const char* mqtt_pass     = "Anggaiot123";
const char* topic_publish = "kelompok20/pzem/data";

// ===== 2. KONFIGURASI PIN HARDWARE SERIAL PZEM =====
// Menggunakan HardwareSerial 2 bawaan ESP32
#define PZEM_RX_PIN 16 // Hubungkan ke TX modul PZEM
#define PZEM_TX_PIN 17 // Hubungkan ke RX modul PZEM
PZEM004Tv30 pzem(Serial2, PZEM_RX_PIN, PZEM_TX_PIN);

WiFiClientSecure espClient;
PubSubClient client(espClient);

unsigned long lastSend = 0;
const unsigned long intervalSend = 3000; // Kirim data tiap 3 detik

// ===== FUNGSI KONEKSI WI-FI =====
void connectWifi() {
  Serial.print("Menghubungkan ke Wi-Fi: ");
  Serial.println(ssid);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  int retries = 0;
  while (WiFi.status() != WL_CONNECTED && retries < 25) {
    delay(500);
    Serial.print(".");
    retries++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WIFI] Terhubung! IP: " + WiFi.localIP().toString());
  } else {
    Serial.println("\n[WIFI] Belum terhubung, akan dicoba ulang di background...");
  }
}

// ===== FUNGSI KONEKSI MQTT (NON-BLOCKING) =====
void reconnectMqtt() {
  if (!client.connected()) {
    Serial.print("[MQTT] Menghubungkan ke EMQX Cloud...");
    String clientId = "ESP32_PZEM_" + String(random(0xffff), HEX);

    if (client.connect(clientId.c_str(), mqtt_user, mqtt_pass)) {
      Serial.println(" Berhasil terhubung!");
    } else {
      Serial.print(" Gagal, rc=");
      Serial.println(client.state());
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n=== ESP32 #1 PZEM MONITORING LISTRIK ===");

  connectWifi();

  // Mode aman TLS tanpa validasi sertifikat CA publik (sangat ringan untuk ESP32)
  espClient.setInsecure();
  client.setServer(mqtt_server, mqtt_port);
  client.setBufferSize(512); // Buffer cukup untuk payload JSON
}

void loop() {
  // Jaga koneksi Wi-Fi & MQTT agar tetap hidup secara non-blocking
  if (WiFi.status() == WL_CONNECTED) {
    if (!client.connected()) {
      static unsigned long lastReconnectAttempt = 0;
      if (millis() - lastReconnectAttempt > 5000) {
        lastReconnectAttempt = millis();
        reconnectMqtt();
      }
    } else {
      client.loop();
    }
  } else {
    static unsigned long lastWifiAttempt = 0;
    if (millis() - lastWifiAttempt > 10000) {
      lastWifiAttempt = millis();
      WiFi.reconnect();
    }
  }

  // Rutinitas pembacaan sensor PZEM secara berkala
  unsigned long now = millis();
  if (now - lastSend >= intervalSend) {
    lastSend = now;

    float voltage     = pzem.voltage();
    float current     = pzem.current();
    float power       = pzem.power();
    float energy      = pzem.energy();
    float frequency   = pzem.frequency();
    float pf          = pzem.pf();

    // Validasi pembacaan PZEM
    if (isnan(voltage)) {
      Serial.println("[PZEM] Peringatan: Sensor PZEM tidak terbaca / kabel lepas!");
      return;
    }

    Serial.printf("[PZEM] V: %.1fV | I: %.2fA | P: %.1fW | E: %.3fkWh | PF: %.2f\n",
                  voltage, current, power, energy, pf);

    // Siapkan Payload JSON untuk Laravel & Dashboard
    StaticJsonDocument<256> doc;
    doc["device_id"]   = "esp32_pzem_01";
    doc["voltage"]     = voltage;
    doc["current"]     = current;
    doc["power_watt"]  = power;
    doc["power"]       = power;
    doc["energy"]      = energy;
    doc["frequency"]   = isnan(frequency) ? 50.0 : frequency;
    doc["pf"]          = isnan(pf) ? 1.0 : pf;

    char buffer[256];
    serializeJson(doc, buffer);

    if (client.connected()) {
      client.publish(topic_publish, buffer);
      Serial.println("-> Terkirim ke topic: " + String(topic_publish));
    } else {
      Serial.println("-> MQTT belum terhubung, data tidak terkirim");
    }
  }
}

