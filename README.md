# ⚡ Sistem Monitoring Listrik Rumah, Keamanan IoT & Asisten Suara JARVIS AI

Sistem monitoring energi listrik pintar terintegrasi dengan proteksi keamanan lingkungan ruangan, prediksi kecukupan token PLN berbasis Machine Learning, serta asisten suara interaktif (**JARVIS**) yang ditenagai oleh **n8n AI Agent**, **LLM (Google Gemini)**, dan protokol resmi **MCP (Model Context Protocol)** di Python.

---

## 🏛️ Arsitektur Sistem

```
┌────────────────────────────────────────────────────────────────────────┐
│                        LAPISAN PENGGUNA & AI                          │
├───────────────────────────────────┬────────────────────────────────────┤
│   JARVIS AI Voice & Chat Console  │   Dashboard Web Monitoring Laravel │
│   (Web Speech STT / TTS Browser)  │   (Grafik Realtime, Token & Switch)│
└─────────────────┬─────────────────┴──────────────────┬─────────────────┘
                  │                                    │
                  ▼                                    ▼
       [ n8n AI Orchestrator ]              [ Backend Laravel 13 API ]
       (Google Gemini + Memory)             (Database MySQL + Predictions)
                  │                                    │
                  ▼                                    │
     [ Python MCP Server (FastMCP) ]                   │
     (Port 8001 / SSE Transport)                       │
                  │                                    │
                  └─────────────────┬──────────────────┘
                                    │
                                    ▼
       ┌────────────────────────────────────────────────────────┐
       │             EMQX CLOUD MQTT BROKER (TLS 8883)          │
       │                 Prefix Topic: kelompok20/#             │
       └────────────────────────────┬───────────────────────────┘
                                    │
                  ┌─────────────────┴─────────────────┐
                  ▼                                   ▼
          [ ESP32 #1 (Energi) ]               [ ESP32 #2 (Keamanan) ]
       - Sensor PZEM-004T v3.0             - Sensor Gas MQ-2
       - Volt, Ampere, Watt, kWh           - Sensor Suhu/Hum DHT22
                                           - Lampu / Relay LED
                                           - Alarm Buzzer (Safety Lokal)
```

---

## 🚀 Fitur Unggulan

1. **Dual-ESP32 Architecture:**
   - **ESP32 #1 (Listrik):** Membaca tegangan, arus, daya aktif, energi kumulatif kWh, dan power factor secara non-blocking.
   - **ESP32 #2 (Keamanan & Kontrol):** Memantau kebocoran gas LPG/asap (MQ-2) dan suhu ruangan (DHT22). Memiliki fitur **Local Safety Offline**: buzzer berbunyi otomatis saat gas bocor tanpa memerlukan internet.
2. **Kendali Ganda (Dual-Interface Control):**
   - Mengontrol saklar lampu/relay dan alarm buzzer melalui tombol interaktif di web dashboard.
   - Atau mengontrol secara alami lewat perintah suara ke Jarvis (*"Jarvis, matikan lampu"*).
3. **Prediksi Token Listrik Machine Learning:**
   - Model time-series (`ml/model_listrik.pkl` & `ml/predict.py`) untuk memprediksi ketahanan token PLN berbagai nominal (Rp 20.000 - Rp 1.000.000) dan estimasi tanggal alarm meteran berbunyi.
4. **Asisten AI Suara (JARVIS Console):**
   - Web Speech Recognition (Bahasa Indonesia `id-ID`) & Web Speech Synthesis.
   - Visualizer reaktor suara berputar dengan status dinamis (*Listening*, *Thinking*, *Responding*).
   - Dilengkapi fallback input chat teks cepat.
5. **Model Context Protocol (MCP) Server Python:**
   - Menyediakan tool telemetri, kendali aktuator MQTT, dan kalkulasi prediksi token untuk dikonsumsi n8n.

---

## 📡 Daftar Topic MQTT (EMQX Cloud)

- **Broker Address:** `j3a33332.ala.asia-southeast1.emqxsl.com`
- **Port:** `8883` (TLS/SSL)
- **Kredensial:** Username `kelompok20` / Password akun EMQX Anda.

| No | Topic | Arah Aliran | Fungsi |
|---|---|---|---|
| 1 | `kelompok20/pzem/data` | ESP32 #1 ➔ Broker ➔ Laravel | Telemetri Volt, Watt, Ampere, kWh |
| 2 | `kelompok20/safety/data` | ESP32 #2 ➔ Broker ➔ Laravel | Telemetri Gas (ppm), Suhu (°C), Hum (%) |
| 3 | `kelompok20/control/command` | Web / Jarvis ➔ Broker ➔ ESP32 #2 | Perintah saklar: `{"target":"relay_led","action":"ON"}` |
| 4 | `kelompok20/control/status` | ESP32 #2 ➔ Broker ➔ Laravel | Konfirmasi status feedback aktuator |

---

## 🛠️ Persyaratan Sistem (Prerequisites)

- **PHP** >= 8.3 & **Composer**
- **Node.js** >= 18 & **NPM**
- **Python** 3.10+ (dengan `fastmcp`, `requests`, `joblib`, `pandas`)
- **MySQL / MariaDB** (Laragon / XAMPP)
- **Docker Desktop** (untuk menjalankan n8n) atau `npx n8n`
- **Arduino IDE** (untuk upload firmware ESP32)

---

## 🔄 Langkah Cepat untuk Teman / Rekan Tim (Setelah Git Pull)

Jika rekan tim Anda baru saja melakukan `git pull` dari repositori ini, jalankan **4 perintah wajib** berikut di terminal laptop mereka agar sistem siap 100%:

```powershell
# 1. Pasang dependensi pustaka MQTT dan library PHP terbaru
composer install

# 2. Perbarui database lokal (membuat tabel safety_logs & device_states)
php artisan migrate

# 3. Jalankan server web dashboard Laravel
php artisan serve --host=0.0.0.0 --port=8000

# 4. Jalankan listener MQTT di terminal terpisah (agar data dari ESP32 PZEM tertangkap)
php artisan mqtt:listen
```

> **Catatan untuk Rekan Tim:**  
> Begitu 4 langkah di atas dijalankan dan ESP32 #1 dihubungkan ke listrik, buka browser di `http://localhost:8000`. Card Tegangan, Arus, Daya Watt, dan Grafik Real-time akan **langsung otomatis menampilkan data asli dari sensor PZEM**.

---

## 💻 Panduan Menjalankan Seluruh Sistem (Lengkap)

Jalankan layanan-layanan berikut di terminal terpisah:

### 1. Database & Server Web Laravel (Terminal 1)
Pastikan MySQL (di Laragon/XAMPP) sudah aktif, lalu jalankan:
```powershell
# Migrasi database jika belum
php artisan migrate

# Jalankan web server Laravel
php artisan serve --host=0.0.0.0 --port=8000
```
> Web Dashboard dapat diakses di: **`http://localhost:8000`** atau **`http://127.0.0.1:8000`**.

---

### 2. MQTT Background Listener Laravel (Terminal 2)
Mendengarkan data sensor dari kedua ESP32 dan menyimpannya otomatis ke database:
```powershell
php artisan mqtt:listen
```

---

### 3. Server MCP Python (Terminal 3)
Menjalankan server protokol MCP untuk melayani n8n AI Agent:
```powershell
python ml/mcp_server.py --sse
```
> Server MCP akan standby di: **`http://127.0.0.1:8001/sse`**

---

### 4. Workflow n8n AI Agent (Terminal 4 / Docker)
Pastikan n8n berjalan (misal via Docker):
```powershell
docker run -it --rm --name n8n -p 5678:5678 -v n8n_data:/home/node/.n8n docker.n8n.io/n8nio/n8n
```
Di dalam n8n:
1. Hubungkan node **MCP Client** (mode SSE) ke URL:
   `http://host.docker.internal:8001/sse`
2. Pastikan node **Webhook** URL-nya cocok dengan `N8N_WEBHOOK_URL` di `dashboard.blade.php`.
3. Aktifkan workflow (**Active / Publish**).

---

### 5. Flash Firmware ESP32 (Arduino IDE)

#### A. ESP32 #1 (Listrik PZEM):
1. Buka file: `firmware/esp32_1_pzem/esp32_1_pzem.ino`.
2. Sesuaikan nama Wi-Fi dan Password:
   ```cpp
   const char* ssid     = "NAMA_WIFI";
   const char* password = "PASSWORD_WIFI";
   const char* mqtt_user = "kelompok20";
   const char* mqtt_pass = "PASSWORD_EMQX";
   ```
3. Pasang pin PZEM: `TX` ke GPIO 16, `RX` ke GPIO 17.
4. Upload program ke board ESP32 #1.

#### B. ESP32 #2 (Keamanan MQ-2 & Kontrol):
1. Buka file: `firmware/esp32_2_safety_control/esp32_2_safety_control.ino`.
2. Sesuaikan nama Wi-Fi dan Password (bisa Wi-Fi yang berbeda dengan ESP32 #1).
3. Pasang sensor:
   - MQ-2 `A0` ➔ GPIO 34
   - DHT22 `Data` ➔ GPIO 4
   - Relay / LED ➔ GPIO 2
   - Buzzer ➔ GPIO 18
4. Upload program ke board ESP32 #2.

---

## 🎤 Contoh Perintah Suara / Chat ke JARVIS AI

Di Dashboard web, klik layar `[SYSTEM STANDBY]`, lalu tekan tombol bicara atau ketik di kolom teks:

- *"Jarvis, berapa konsumsi daya listrik rumah saya saat ini?"*  
  *(AI memanggil tool MCP untuk mengambil data Volt, Ampere, Watt).*
- *"Bagaimana kondisi keamanan ruangan, apakah ada kebocoran gas?"*  
  *(AI memeriksa kadar gas MQ-2 dan suhu ruangan).*
- *"Tolong nyalakan lampu relay."*  
  *(AI memanggil kontrol aktuator ➔ sinyal terkirim ke MQTT ➔ Relay LED menyala seketika).*
- *"Berapa lama token listrik 50 ribu bisa bertahan?"*  
  *(AI mengeksekusi model Machine Learning untuk menghitung ketahanan token).*

---

## 📂 Struktur Direktori Proyek

```text
├── app/
│   ├── Console/Commands/
│   │   └── MqttSubscribe.php      # Worker Artisan penangkap topic MQTT
│   ├── Http/Controllers/Api/
│   │   ├── SystemApiController.php# Endpoint status ringkasan & kontrol
│   │   └── PredictionController.php
│   ├── Models/
│   │   ├── SensorLog.php          # Data PZEM
│   │   ├── SafetyLog.php          # Data MQ-2 & DHT22
│   │   └── DeviceState.php        # Status Relay & Buzzer
│   └── Services/
│       └── MqttService.php        # Helper publikasi MQTT aman TLS
├── database/migrations/           # Skema tabel database MySQL
├── firmware/
│   ├── esp32_1_pzem/              # Source code Arduino IDE ESP32 #1
│   └── esp32_2_safety_control/    # Source code Arduino IDE ESP32 #2
├── ml/
│   ├── mcp_server.py              # Server FastMCP Python (Port 8001 SSE)
│   ├── model_listrik.pkl          # Model ML Prediksi
│   └── predict.py                 # Skrip kalkulasi simulasi token
├── resources/views/
│   └── dashboard.blade.php        # UI Dashboard Web & JARVIS HUD
└── routes/
    └── api.php                    # Rute API RESTful
```

---
**Pengembang:** Tim Proyek Teknologi Informasi (PTI) dan Tim Internet Of Things (IOT) © 2026
