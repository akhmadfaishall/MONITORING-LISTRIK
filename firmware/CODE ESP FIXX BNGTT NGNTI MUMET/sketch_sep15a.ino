
#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <PubSubClient.h>
#include <PZEM004Tv30.h>
#include <ArduinoJson.h>
#include <math.h>

// =====================================
// WIFI
// =====================================
const char* ssid = "Hybrid 802.11be";
const char* wifiPassword = "akuyolali";

// =====================================
// MQTT EMQX CLOUD
// =====================================
const char* mqttServer = "j3a33332.ala.asia-southeast1.emqxsl.com";
const int mqttPort = 8883;

const char* mqttUser = "kelompok20";
const char* mqttPassword = "Anggaiot123";

const char* mqttTopic = "kelompok20/pzem/data";
const char* deviceId = "esp32_pzem_01";

// =====================================
// PZEM-004T V4
// =====================================
#define PZEM_RX 16
#define PZEM_TX 17

PZEM004Tv30 pzem(Serial2, PZEM_RX, PZEM_TX);

// =====================================
// WIFI & MQTT CLIENT
// =====================================
WiFiClientSecure espClient;
PubSubClient client(espClient);

unsigned long lastPublish = 0;
const unsigned long publishInterval = 3000;

// =====================================
// CONNECT WIFI - DHCP
// =====================================
void connectWiFi() {
  if (WiFi.status() == WL_CONNECTED) return;

  Serial.println("\nMenghubungkan ke WiFi...");

  WiFi.mode(WIFI_STA);
  WiFi.setAutoReconnect(true);

  // Tidak menggunakan IP statis.
  // ESP32 memperoleh IP melalui DHCP.
  WiFi.begin(ssid, wifiPassword);

  int attempts = 0;

  while (WiFi.status() != WL_CONNECTED && attempts < 40) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n\nWiFi berhasil terhubung!");

    Serial.print("IP ESP32 : ");
    Serial.println(WiFi.localIP());

    Serial.print("Gateway  : ");
    Serial.println(WiFi.gatewayIP());

    Serial.print("Subnet   : ");
    Serial.println(WiFi.subnetMask());

    Serial.print("DNS      : ");
    Serial.println(WiFi.dnsIP());

    Serial.print("MAC      : ");
    Serial.println(WiFi.macAddress());
  } else {
    Serial.println("\nWiFi gagal terhubung!");
  }
}

// =====================================
// TEST DNS
// =====================================
void testDNS() {
  IPAddress ip;

  Serial.println("\n========== TES DNS ==========");

  Serial.print("Google: ");

  if (WiFi.hostByName("google.com", ip)) {
    Serial.println(ip);
  } else {
    Serial.println("GAGAL");
  }

  Serial.print("EMQX: ");

  if (WiFi.hostByName(mqttServer, ip)) {
    Serial.println(ip);
  } else {
    Serial.println("GAGAL");
  }
}

// =====================================
// TEST TCP
// =====================================
void testTCP() {
  WiFiClient testClient;
  IPAddress brokerIP;

  Serial.println("\n========== TES TCP ==========");

  Serial.println("Tes internet 1.1.1.1 port 443...");

  if (testClient.connect("1.1.1.1", 443)) {
    Serial.println("TCP internet BERHASIL");
    testClient.stop();
  } else {
    Serial.println("TCP internet GAGAL");
  }

  if (WiFi.hostByName(mqttServer, brokerIP)) {
    Serial.print("Tes EMQX ");
    Serial.print(brokerIP);
    Serial.println(" port 8883...");

    if (testClient.connect(brokerIP, mqttPort)) {
      Serial.println("TCP EMQX BERHASIL");
      testClient.stop();
    } else {
      Serial.println("TCP EMQX GAGAL");
    }
  } else {
    Serial.println("DNS EMQX gagal, tes TCP dilewati.");
  }
}

// =====================================
// CONNECT MQTT
// =====================================
void connectMQTT() {
  if (WiFi.status() != WL_CONNECTED) return;
  if (client.connected()) return;

  Serial.println("\nMenghubungkan ke MQTT EMQX...");

  String clientId = "ESP32-" +
                    String((uint32_t)ESP.getEfuseMac(), HEX);

  if (client.connect(
        clientId.c_str(),
        mqttUser,
        mqttPassword
      )) {

    Serial.println("MQTT BERHASIL TERHUBUNG!");

  } else {
    Serial.print("MQTT GAGAL. Status: ");
    Serial.println(client.state());
  }
}

// =====================================
// READ AND SEND PZEM DATA
// =====================================
void sendPZEMData() {
  float voltage = pzem.voltage();
  float current = pzem.current();
  float power = pzem.power();
  float energy = pzem.energy();
  float frequency = pzem.frequency();
  float pf = pzem.pf();

  if (isnan(voltage) ||
      isnan(current) ||
      isnan(power) ||
      isnan(energy) ||
      isnan(frequency) ||
      isnan(pf)) {

    Serial.println("Gagal membaca data PZEM!");
    Serial.println("Periksa kabel RX/TX, GND, dan suplai PZEM.");
    return;
  }

  StaticJsonDocument<512> doc;

  doc["device_id"] = deviceId;
  doc["voltage"] = voltage;
  doc["current"] = current;
  doc["power"] = power;
  doc["power_watt"] = power;
  doc["energy"] = energy;
  doc["frequency"] = frequency;
  doc["pf"] = pf;
  doc["power_factor"] = pf;
  doc["apparent_power"] = voltage * current;

  char payload[512];

  serializeJson(doc, payload);

  if (client.publish(mqttTopic, payload)) {
    Serial.println("\nData berhasil dikirim ke MQTT:");
    Serial.println(payload);
  } else {
    Serial.println("Gagal mengirim data MQTT!");
  }
}

// =====================================
// SETUP
// =====================================
void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n==================================");
  Serial.println("MONITORING LISTRIK RUMAH F3");
  Serial.println("ESP32 + PZEM-004T V4 + MQTT");
  Serial.println("==================================");

  // TLS untuk pengujian.
  // Untuk produksi, gunakan verifikasi sertifikat CA.
  espClient.setInsecure();

  client.setServer(mqttServer, mqttPort);
  client.setBufferSize(512);
  client.setKeepAlive(30);
  client.setSocketTimeout(10);

  connectWiFi();

  if (WiFi.status() == WL_CONNECTED) {
    delay(1000);
    testDNS();
    testTCP();
  }
}

// =====================================
// LOOP
// =====================================
void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
    delay(1000);
    return;
  }

  if (!client.connected()) {
    connectMQTT();
    delay(1000);
    return;
  }

  client.loop();

  if (millis() - lastPublish >= publishInterval) {
    lastPublish = millis();
    sendPZEMData();
  }
}
