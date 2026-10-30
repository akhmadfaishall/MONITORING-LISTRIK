"""
=============================================================================
JARVIS MCP SERVER (Model Context Protocol)
Monitoring Listrik, Keamanan IoT & Kontrol Hardware
=============================================================================
Server ini menyediakan Tools untuk AI Agent (n8n / LLM) berbasis protokol MCP:
1. cek_status_listrik_dan_keamanan - Membaca data real-time sensor PZEM & MQ-2/DHT22
2. kontrol_saklar_perangkat - Menyalakan/mematikan Relay Lampu atau Buzzer via MQTT
3. get_prediksi_token_pln - Mendapatkan hasil estimasi daya tahan token dan tarif
=============================================================================
"""

import os
import sys
import json
import requests
from fastmcp import FastMCP

# Inisialisasi Server MCP
mcp = FastMCP("JarvisElectricalSafetyMCP")

# Menggunakan Environment Variable jika ada (untuk deployment Cloud Railway), fallback ke localhost
LARAVEL_BASE_URL = os.environ.get("LARAVEL_BASE_URL", "http://127.0.0.1:8000").rstrip("/")


@mcp.tool()
def cek_status_listrik_dan_keamanan() -> str:
    """
    Mengambil data telemetri terkini dari sistem monitoring:
    - Listrik (ESP32 #1 - PZEM): Tegangan (Volt), Arus (Ampere), Daya (Watt), Akumulasi kWh.
    - Keamanan (ESP32 #2 - MQ-2 & DHT22): Kadar Gas (ppm), Suhu (C), Kelembaban (%), Status Bahaya.
    - Status Aktuator: Kondisi Lampu Relay LED (ON/OFF) dan Buzzer (ON/OFF).
    Gunakan tool ini ketika user bertanya mengenai kondisi listrik, keamanan gas, atau suhu ruangan.
    """
    url = f"{LARAVEL_BASE_URL}/api/system/status-summary"
    try:
        response = requests.get(url, timeout=5)
        if response.status_code == 200:
            data = response.json()
            listrik = data.get("listrik", {})
            keamanan = data.get("keamanan", {})
            perangkat = data.get("perangkat", {})

            return (
                f"=== STATUS SISTEM TERKINI ===\n"
                f"[KELISTRIKAN - PZEM]\n"
                f"- Tegangan: {listrik.get('voltage', 0)} Volt\n"
                f"- Arus: {listrik.get('current', 0)} Ampere\n"
                f"- Daya Saat Ini: {listrik.get('power_watt', 0)} Watt\n"
                f"- Akumulasi Energi: {listrik.get('energy_kwh', 0)} kWh\n"
                f"- Pembaruan Terakhir: {listrik.get('last_updated', '-')}\n\n"
                f"[KEAMANAN LINGKUNGAN]\n"
                f"- Kadar Gas MQ-2: {keamanan.get('gas_ppm', 0)} ppm\n"
                f"- Status Gas: {keamanan.get('status', 'AMAN')}\n"
                f"- Suhu Ruangan: {keamanan.get('temperature', 0)} C\n"
                f"- Kelembaban: {keamanan.get('humidity', 0)}% RH\n\n"
                f"[STATUS PERANGKAT / SAKLAR]\n"
                f"- Lampu/Relay LED: {perangkat.get('relay_led', 'OFF')}\n"
                f"- Alarm Buzzer: {perangkat.get('buzzer', 'OFF')}\n"
            )
        else:
            return f"Error: Gagal mengambil data dari Laravel (Status: {response.status_code})"
    except Exception as e:
        return f"Error koneksi ke API Laravel: {str(e)}"


@mcp.tool()
def kontrol_saklar_perangkat(device: str, action: str) -> str:
    """
    Mengontrol perangkat keras aktuator (Lampu Relay LED atau Alarm Buzzer) melalui MQTT.
    
    Argumen:
    - device: Nama perangkat yang ingin dikontrol. Pilihan: 'relay_led' (untuk lampu/relay) atau 'buzzer' (untuk alarm).
    - action: Perintah aksi. Pilihan: 'ON' (menyalakan) atau 'OFF' (mematikan).
    
    Gunakan tool ini ketika user meminta menyalakan atau mematikan lampu/alarm.
    Contoh: "Jarvis, tolong matikan lampu" -> device='relay_led', action='OFF'
    """
    device_clean = device.lower().strip()
    action_clean = action.upper().strip()

    # Normalisasi nama perangkat jika user menyebut istilah umum
    if "lampu" in device_clean or "relay" in device_clean or "led" in device_clean:
        target_device = "relay_led"
    elif "buzzer" in device_clean or "alarm" in device_clean:
        target_device = "buzzer"
    else:
        target_device = device_clean

    if action_clean not in ["ON", "OFF"]:
        return "Gagal: Aksi harus bernilai 'ON' atau 'OFF'."

    url = f"{LARAVEL_BASE_URL}/api/device/control"
    payload = {
        "device": target_device,
        "state": action_clean
    }

    try:
        response = requests.post(url, json=payload, timeout=5)
        if response.status_code == 200:
            res_data = response.json()
            return f"Sukses: Perangkat '{target_device}' berhasil diatur menjadi '{action_clean}' via MQTT."
        else:
            return f"Error: Gagal mengirim kontrol ke server (Status {response.status_code}): {response.text}"
    except Exception as e:
        return f"Error koneksi kontrol ke API: {str(e)}"


@mcp.tool()
def get_prediksi_token_pln() -> str:
    """
    Mengambil data estimasi prediksi konsumsi listrik harian/bulanan dan simulasi ketahanan token listrik PLN.
    Gunakan tool ini ketika user bertanya:
    - "Berapa lama token 50 ribu bisa bertahan?"
    - "Berapa perkiraan tagihan/kebutuhan listrik bulan ini?"
    - "Kapan kira-kira alarm meteran berbunyi?"
    """
    url = f"{LARAVEL_BASE_URL}/api/predict"
    try:
        response = requests.get(url, timeout=8)
        if response.status_code == 200:
            data = response.json()
            return f"Data Hasil Prediksi ML Token PLN:\n{json.dumps(data, indent=2, ensure_ascii=False)}"
        else:
            return f"Error mengambil data prediksi: status {response.status_code}"
    except Exception as e:
        return f"Error koneksi ke API Prediksi: {str(e)}"


if __name__ == "__main__":
    # Jalankan server MCP
    # Jika dijalankan dengan flag --sse, akan membuka server HTTP SSE
    if "--sse" in sys.argv:
        port = int(os.environ.get("PORT", 8001))
        host = os.environ.get("HOST", "0.0.0.0")
        print(f"[JARVIS MCP] Menjalankan server SSE di http://{host}:{port}/sse ...", file=sys.stderr)
        mcp.run(transport="sse", host=host, port=port)
    else:
        # Default: mode stdio untuk integrasi langsung
        mcp.run(transport="stdio")

