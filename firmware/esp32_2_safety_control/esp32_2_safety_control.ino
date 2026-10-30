/*
  =============================================================================
  PROYEK PTI 2026 - MONITORING LISTRIK & KEAMANAN RUMAH
  FIRMWARE ESP32 #2 : KEAMANAN LINGKUNGAN & KONTROL AKTUATOR
  =============================================================================
  Perangkat Keras : Sensor MQ-2, Sensor DHT22, LED (Simulasi Relay), Buzzer Alarm
  Topic Publish   : kelompok20/safety/data      (Telemetri Gas & Suhu)
  Topic Subscribe : kelompok20/control/command  (Menerima Perintah dari Web/Jarvis)
  Topic Feedback  : kelompok20/control/status   (Konfirmasi Status Eksekusi)
  Broker          : EMQX Cloud (j3a33332.ala.asia-southeast1.emqxsl.com:8883)
  =============================================================================
*/

#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <PubSubClient.h>
#include <DHT.h>
#include <ArduinoJson.h>

// ===== 1. KONFIGURASI PIN PERANGKAT KERAS =====
#define PIN_MQ2         34   // Input Analog ADC untuk Sensor Gas MQ-2
#define PIN_DHT         4    // Pin Data untuk Sensor Suhu/Kelembaban DHT22
#define DHTTYPE         DHT22
#define PIN_RELAY_LED   2    // Pin Output untuk Lampu / Relay LED
#define PIN_BUZZER      18   // Pin Output untuk Alarm Buzzer

// Batas aman kadar gas (ADC 0 - 4095). Baseline udara normal: 400-750. Jika >= 1000, alarm berbunyi lokal otomatis
#define GAS_THRESHOLD   1000

// ===== 2. KONFIGURASI WI-FI & BROKER MQTT =====
const char* ssid            = "NAMA_WIFI_ANDA";       // Ganti dengan SSID Wi-Fi
const char* password        = "PASSWORD_WIFI_ANDA";   // Ganti dengan Password Wi-Fi
const char* mqtt_server     = "j3a33332.ala.asia-southeast1.emqxsl.com";
const int   mqtt_port       = 8883;
const char* mqtt_user       = "kelompok20";
const char* mqtt_pass       = "Anggaiot123";

const char* topic_telemetry = "kelompok20/safety/data";
const char* topic_command   = "kelompok20/control/command";
const char* topic_status    = "kelompok20/control/status";

DHT dht(PIN_DHT, DHTTYPE);
WiFiClientSecure espClient;
PubSubClient client(espClient);

// Status Perangkat Saat Ini
bool stateRelay       = false; // false = OFF, true = ON
bool stateBuzzer      = false; // false = OFF, true = ON (kontrol manual)
bool isGasDangerLocal = false; // Status bahaya gas lokal

unsigned long lastSend = 0;
const unsigned long intervalSend = 3000; // Kirim data telemetri tiap 3 detik

// ===== FUNGSI MENGIRIM KONFIRMASI STATUS (FEEDBACK) KE MQTT =====
void sendFeedback(String device, String state) {
  if (!client.connected()) return;

  StaticJsonDocument<128> doc;
  doc["device"] = device;
  doc["state"]  = state;

  char buf[128];
  serializeJson(doc, buf);
  client.publish(topic_status, buf);
  Serial.printf("[FEEDBACK] Terkirim: %s = %s\n", device.c_str(), state.c_str());
}

// ===== CALLBACK SAAT MENERIMA INSTRUKSI (DARI WEB DASHBOARD / JARVIS AI) =====
void mqttCallback(char* topic, byte* payload, unsigned int length) {
  String message = "";
  for (unsigned int i = 0; i < length; i++) {
    message += (char)payload[i];
  }
  Serial.printf("\n[MQTT INSTRUKSI DITERIMA] Topic: %s | Pesan: %s\n", topic, message.c_str());

  StaticJsonDocument<256> doc;
  DeserializationError error = deserializeJson(doc, message);
  if (error) {
    Serial.println("[ERROR] Gagal parsing JSON instruksi!");
    return;
  }

  String target = doc["target"] | doc["device"] | "";
  String action = doc["action"] | doc["state"] | "";
  target.toLowerCase();
  action.toUpperCase();

  // A. KONTROL LAMPU / RELAY LED
  if (target == "relay_led" || target == "lampu" || target == "led") {
    stateRelay = (action == "ON");
    digitalWrite(PIN_RELAY_LED, stateRelay ? HIGH : LOW);
    Serial.println("[EKSEKUSI] Status Relay LED sekarang: " + String(stateRelay ? "ON" : "OFF"));
    sendFeedback("relay_led", stateRelay ? "ON" : "OFF");
  }

  // B. KONTROL MANUAL ALARM BUZZER
  else if (target == "buzzer" || target == "alarm") {
    stateBuzzer = (action == "ON");
    // Bunyikan buzzer jika dinyalakan manual atau sedang dalam bahaya gas
    digitalWrite(PIN_BUZZER, (stateBuzzer || isGasDangerLocal) ? HIGH : LOW);
    Serial.println("[EKSEKUSI] Status Buzzer sekarang: " + String(stateBuzzer ? "ON" : "OFF"));
    sendFeedback("buzzer", stateBuzzer ? "ON" : "OFF");
  }
}

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

// ===== FUNGSI KONEKSI MQTT & SUBSCRIBE =====
void reconnectMqtt() {
  if (!client.connected()) {
    Serial.print("[MQTT] Menghubungkan ke EMQX Cloud...");
    String clientId = "ESP32_SAFETY_" + String(random(0xffff), HEX);

    if (client.connect(clientId.c_str(), mqtt_user, mqtt_pass)) {
      Serial.println(" Berhasil terhubung!");
      // Wajib subscribe ke topic command kontrol
      client.subscribe(topic_command);
      Serial.println("[MQTT] Berhasil subscribe ke topic: " + String(topic_command));
    } else {
      Serial.print(" Gagal, rc=");
      Serial.println(client.state());
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n=== ESP32 #2 KEAMANAN & KONTROL AKTUATOR ===");

  // Inisialisasi Pin Output
  pinMode(PIN_RELAY_LED, OUTPUT);
  pinMode(PIN_BUZZER, OUTPUT);
  digitalWrite(PIN_RELAY_LED, LOW);
  digitalWrite(PIN_BUZZER, LOW);

  // Inisialisasi Sensor DHT22
  dht.begin();

  // Koneksi Jaringan
  connectWifi();

  espClient.setInsecure();
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(mqttCallback);
  client.setBufferSize(512);
}

void loop() {
  // Jaga koneksi MQTT secara non-blocking
  if (WiFi.status() == WL_CONNECTED) {
    if (!client.connected()) {
      static unsigned long lastReconnect = 0;
      if (millis() - lastReconnect > 5000) {
        lastReconnect = millis();
        reconnectMqtt();
      }
    } else {
      client.loop(); // Memproses pesan masuk dari MQTT
    }
  }

  // ===== 1. LOCAL SAFETY LOGIC (KEAMANAN MANDIRI TANPA INTERNET) =====
  int gasRaw = analogRead(PIN_MQ2);
  isGasDangerLocal = (gasRaw > GAS_THRESHOLD);

  if (isGasDangerLocal) {
    // Gas bocor: langsung bunyikan buzzer secara lokal seketika!
    digitalWrite(PIN_BUZZER, HIGH);
  } else if (!stateBuzzer) {
    // Jika tidak bahaya dan buzzer tidak dinyalakan manual lewat web/suara, matikan
    digitalWrite(PIN_BUZZER, LOW);
  }

  // ===== 2. KIRIM TELEMETRI KE CLOUD TIAP 3 DETIK =====
  unsigned long now = millis();
  if (now - lastSend >= intervalSend) {
    lastSend = now;

    float temp = dht.readTemperature();
    float hum  = dht.readHumidity();

    if (isnan(temp)) temp = 28.5; // Nilai fallback jika DHT22 baru menyala
    if (isnan(hum))  hum  = 65.0;

    Serial.printf("[SENSOR] Gas: %d ppm (%s) | Suhu: %.1f C | Hum: %.1f%% | Relay: %s | Buzzer: %s\n",
                  gasRaw, isGasDangerLocal ? "BAHAYA" : "AMAN", temp, hum,
                  stateRelay ? "ON" : "OFF",
                  (stateBuzzer || isGasDangerLocal) ? "ON" : "OFF");

    // Format Payload Telemetri untuk Laravel & Dashboard
    StaticJsonDocument<256> doc;
    doc["device_id"]   = "esp32_safety_02";
    doc["gas_ppm"]     = gasRaw;
    doc["temperature"] = temp;
    doc["humidity"]    = hum;
    doc["gas_danger"]  = isGasDangerLocal;
    doc["buzzer"]      = (stateBuzzer || isGasDangerLocal) ? "ON" : "OFF";
    doc["relay_led"]   = stateRelay ? "ON" : "OFF";

    char buffer[256];
    serializeJson(doc, buffer);

    if (client.connected()) {
      client.publish(topic_telemetry, buffer);
      Serial.println("-> Telemetri terkirim ke: " + String(topic_telemetry));
    }
  }
}

