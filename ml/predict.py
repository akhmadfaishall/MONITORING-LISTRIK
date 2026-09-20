from datetime import datetime, timedelta
import json
import sys
import warnings
import joblib   
import pandas as pd 

# Menyembunyikan pesan peringatan versi library agar output bersih
warnings.filterwarnings('ignore')

nama_model = 'model_listrik.pkl'

def muat_model():
    # Memuat model linear regression yang sudah dilatih
    try:
        model = joblib.load(nama_model)
        return model 
    except Exception as e:
        print(f'Error memuat model {nama_model}: {e}', file=sys.stderr)
        sys.exit(1)

def hitung_estimasi(kwh_laju_harian, total_kwh_bulanan):
    # Menghitung daya tahan token, persentase kebutuhan sebulan, dan alarm
    tarif_per_kwh = 1352.00  # Tarif resmi PLN R-1/900 VA RTM (Rp / kWh)
    batas_alarm_kwh = 5.0    # Standar meteran PLN berbunyi saat tersisa 5 kWh

    nominal_list = [20000, 50000, 100000, 200000, 500000, 1000000]
    paket_token = {}

    for nom in nominal_list:
        kwh_dapat = round(nom / tarif_per_kwh, 2)

        # Daya tahan total berdasarkan rata-rata konsumsi harian bulanan
        hari_tahan = round(kwh_dapat / kwh_laju_harian, 1)

        # Berapa persen token ini mencukupi kebutuhan 1 bulan (30 hari)
        persen_bulanan = round((kwh_dapat / total_kwh_bulanan) * 100, 1)

        # Perkiraan kapan alarm berbunyi (sisa 5 kWh)
        kwh_sebelum_alarm = max(0.0, kwh_dapat - batas_alarm_kwh)
        hari_menuju_alarm = kwh_sebelum_alarm / kwh_laju_harian
        waktu_alarm = datetime.now() + timedelta(days=hari_menuju_alarm)
        
        paket_token[f'Token_{nom}'] = {
            'nominal_rp': nom,
            'kwh_didapat': kwh_dapat,
            'daya_tahan_hari': hari_tahan,
            'persen_kebutuhan_sebulan': persen_bulanan,
            'perkiraan_alarm_bunyi': waktu_alarm.strftime(
                "%A, %d %b %Y pukul %H:%M WIB"
            )
        }
    
    return paket_token

def prediksi(input_data=None):
    model = muat_model()

    # Sampel data realistis untuk uji coba
    if input_data is None:
        sekarang = datetime.now()
        input_data = {
            "voltage": 220.0,
            "current": 1.8,
            "power": 380.0,
            "day_of_week": sekarang.weekday(),
            "is_weekend": 1 if sekarang.weekday() in [5, 6] else 0,
            "month": sekarang.month,
            "lag_1_energy": 7.5,
            "rolling_mean_7d": 8.0,
        }
    
    fitur_cols = [
        'voltage', 'current', 'power', 'day_of_week', 'is_weekend', 'month',
        'lag_1_energy', 'rolling_mean_7d'
    ]

    df_input = pd.DataFrame([input_data])[fitur_cols]

    # 1. Prediksi konsumsi harian hari ini dari model ML
    hasil_kwh_hari_ini = float(model.predict(df_input)[0])
    hasil_kwh_hari_ini = max(0.5, round(hasil_kwh_hari_ini, 2))

    # 2. Rata-rata konsumsi harian stabil (Moving Average 70% tren 7 hari + 30% hari ini)
    rolling_7d = input_data.get('rolling_mean_7d', hasil_kwh_hari_ini)
    rata_rata_harian_stabil = round((0.7 * rolling_7d) + (0.3 * hasil_kwh_hari_ini), 2)

    # 3. Estimasi kebutuhan 1 bulan (30 hari) & estimasi biaya
    kebutuhan_sebulan_kwh = round(rata_rata_harian_stabil * 30, 1)
    estimasi_biaya_sebulan_rp = int(kebutuhan_sebulan_kwh * 1352.00)

    # 4. Hitung simulasi token
    estimasi_token = hitung_estimasi(rata_rata_harian_stabil, kebutuhan_sebulan_kwh)

    return {
        "status": "success",
        "waktu_prediksi": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "input_features": input_data,
        "prediksi_hari_ini_kwh": hasil_kwh_hari_ini,
        "rata_rata_harian_stabil_kwh": rata_rata_harian_stabil,
        "estimasi_kebutuhan_sebulan_kwh": kebutuhan_sebulan_kwh,
        "estimasi_biaya_sebulan_rp": estimasi_biaya_sebulan_rp,
        "estimasi_token": estimasi_token,
    }

if __name__ == '__main__':
    is_json = "--json" in sys.argv
    hasil = prediksi()

    if is_json:
        print(json.dumps(hasil))
    else:
        print("=" * 68)
        print("⚡ ANALISIS PREDIKSI KONSUMSI LISTRIK & KEBUTUHAN TOKEN (AI) ⚡")
        print("=" * 68)
        print(f"📅 Waktu Prediksi             : {hasil['waktu_prediksi']}")
        print(f"🎯 Prediksi Konsumsi Hari Ini : {hasil['prediksi_hari_ini_kwh']} kWh / hari")
        print(f"📈 Rata-rata Harian (Stabil)  : {hasil['rata_rata_harian_stabil_kwh']} kWh / hari")
        print(f"🏢 Estimasi Kebutuhan 1 Bulan : {hasil['estimasi_kebutuhan_sebulan_kwh']} kWh (30 Hari)")
        print(f"💰 Estimasi Biaya Listrik/Bln : Rp {hasil['estimasi_biaya_sebulan_rp']:,}")
        print("-" * 68)
        print("📊 SIMULASI KECUKUPAN TOKEN UNTUK KEBUTUHAN 1 BULAN:")
        print("-" * 68)
        for key, val in hasil["estimasi_token"].items():
            print(f"• Token Rp {val['nominal_rp']:,} (~{val['kwh_didapat']} kWh):")
            print(f"   ⏱️  Daya Tahan   : Bertahan ~{val['daya_tahan_hari']} Hari ({val['persen_kebutuhan_sebulan']}% kebutuhan sebulan)")
            print(f"   🔔  Alarm Bunyi  : Perkiraan {val['perkiraan_alarm_bunyi']}")
            print()
        print("=" * 68)
