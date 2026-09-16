<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monitoring Listrik Rumah F3</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>

        /* =====================================================
           ROOT
        ===================================================== */

        :root {

            --bg: #070b12;
            --bg-soft: #0b111b;

            --panel: #0f1723;
            --panel-2: #111c2a;

            --border: #1d2a3a;
            --border-light: #263548;

            --text: #f1f5f9;
            --text-soft: #c5d0dc;
            --muted: #748398;

            --blue: #2f81f7;
            --blue-soft: rgba(47,129,247,.12);

            --cyan: #22d3ee;
            --cyan-soft: rgba(34,211,238,.10);

            --green: #22c55e;
            --green-soft: rgba(34,197,94,.10);

            --orange: #f59e0b;
            --orange-soft: rgba(245,158,11,.10);

            --red: #ef4444;
            --red-soft: rgba(239,68,68,.10);

            --purple: #a855f7;
            --purple-soft: rgba(168,85,247,.10);

            --shadow:
                0 8px 30px rgba(0,0,0,.22);
        }


        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        html {
            background: var(--bg);
        }


        body {

            min-height: 100vh;

            font-family:
                "Inter",
                Arial,
                sans-serif;

            background:
                radial-gradient(
                    circle at 20% 0%,
                    rgba(47,129,247,.07),
                    transparent 28%
                ),
                var(--bg);

            color: var(--text);

            line-height: 1.5;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {

            width: 100%;

            min-height: 76px;

            padding:
                13px
                clamp(16px, 3vw, 40px);

            background:
                rgba(9,14,22,.92);

            border-bottom:
                1px solid var(--border);

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            position: sticky;

            top: 0;

            z-index: 50;

            backdrop-filter: blur(12px);
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 13px;

            min-width: 0;
        }


        .logo {
    width: 46px;
    height: 46px;
    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #ffffff;
    border-radius: 10px;

    padding: 5px;

    overflow: hidden;
}

.logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}


        .brand h1 {

            font-size:
                clamp(15px, 2vw, 19px);

            font-weight: 700;

            letter-spacing: -.35px;

            white-space: nowrap;
        }


        .brand p {

            margin-top: 2px;

            color: var(--muted);

            font-size: 10px;

            font-weight: 500;
        }


        /* ONLINE */

        .connection {
            .connection-divider {
    width: 1px;
    height: 18px;
    background: #334155;
    margin: 0 4px;
}

.live-clock {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #e2e8f0;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

.live-clock-icon {
    font-size: 13px;
    line-height: 1;
}

.clock-zone {
    color: #60a5fa;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
}

            display: flex;

            align-items: center;

            gap: 8px;

            padding: 7px 12px;

            border-radius: 999px;

            background:
                var(--green-soft);

            border:
                1px solid rgba(34,197,94,.22);

            color: #6ee7a0;

            font-size: 10px;

            font-weight: 600;

            white-space: nowrap;
        }


        .dot {

            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--green);

            box-shadow:
                0 0 0 4px
                rgba(34,197,94,.08);

            animation:
                onlinePulse 2s infinite;
        }


        @keyframes onlinePulse {

            0%,100% {
                opacity: 1;
            }

            50% {
                opacity: .45;
            }

        }


        /* =====================================================
           MAIN
        ===================================================== */

        .container {

            width:
                calc(100% - 40px);

            max-width: 1700px;

            margin:
                0 auto;

            padding:
                30px 0 35px;
        }


        .welcome {

            margin-bottom: 22px;
        }


        .welcome h2 {

            font-size:
                clamp(21px, 2.5vw, 28px);

            font-weight: 700;

            letter-spacing: -.7px;
        }


        .welcome p {

            margin-top: 4px;

            color: var(--muted);

            font-size: 12px;
        }


        /* =====================================================
           SENSOR CARDS
        ===================================================== */

        .cards {

            display: grid;

            grid-template-columns:
                repeat(6, minmax(0,1fr));

            gap: 14px;

            margin-bottom: 18px;
        }


        .card {

            position: relative;

            min-width: 0;

            background:
                linear-gradient(
                    145deg,
                    rgba(17,28,42,.98),
                    rgba(12,19,29,.98)
                );

            border:
                1px solid var(--border);

            border-radius: 14px;

            padding: 17px;

            box-shadow:
                var(--shadow);

            overflow: hidden;

            transition:
                transform .2s ease,
                border-color .2s ease,
                box-shadow .2s ease;
        }


        .card:hover {

            transform:
                translateY(-2px);

            border-color:
                var(--border-light);

            box-shadow:
                0 12px 35px rgba(0,0,0,.30);
        }


        .card::after {

            content: "";

            position: absolute;

            left: 0;
            right: 0;

            top: 0;

            height: 2px;

            background:
                var(--accent);
        }


        .card-head {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            margin-bottom: 14px;
        }


        .card-title {

            color: var(--muted);

            font-size: 10px;

            font-weight: 600;

            text-transform: uppercase;

            letter-spacing: .25px;
        }


        .metric-icon {

            width: 34px;
            height: 34px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 9px;

            background:
                var(--icon-bg);

            color:
                var(--accent);
        }


        .metric-icon svg {

            width: 17px;
            height: 17px;

            stroke:
                currentColor;
        }


        .metric-value {

            display: flex;

            align-items: baseline;

            gap: 5px;

            min-width: 0;
        }


        .value {

            font-size:
                clamp(23px, 2.3vw, 30px);

            line-height: 1;

            font-weight: 700;

            letter-spacing: -1px;

            color:
                var(--text);

            font-variant-numeric:
                tabular-nums;

            overflow:
                hidden;

            text-overflow:
                ellipsis;
        }


        .unit {

            color:
                var(--muted);

            font-size: 10px;

            font-weight: 600;
        }


        /* CARD ACCENTS */

        .voltage-card {

            --accent: #3b82f6;

            --icon-bg:
                rgba(59,130,246,.12);
        }


        .current-card {

            --accent: #f59e0b;

            --icon-bg:
                rgba(245,158,11,.12);
        }


        .power-card {

            --accent: #ef4444;

            --icon-bg:
                rgba(239,68,68,.12);
        }


        .energy-card {

            --accent: #22c55e;

            --icon-bg:
                rgba(34,197,94,.12);
        }


        .frequency-card {

            --accent: #a855f7;

            --icon-bg:
                rgba(168,85,247,.12);
        }


        .pf-card {

            --accent: #22d3ee;

            --icon-bg:
                rgba(34,211,238,.12);
        }


        /* =====================================================
           MAIN GRID
        ===================================================== */

        .main-grid {

            display: grid;

            grid-template-columns:
                minmax(0, 1.8fr)
                minmax(340px, .8fr);

            gap: 18px;

            align-items: stretch;
        }


        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .chart-panel { min-width: 0; }
        .system-panel { grid-column: 1 / -1; }
        .charts-grid .chart-wrapper { height: 310px; }
        .connection.offline { background: var(--red-soft); border-color: rgba(239,68,68,.22); color: #fca5a5; }
        .dot.offline { background: var(--red); box-shadow: 0 0 0 4px rgba(239,68,68,.08); animation: offlinePulse 2s infinite; }
        @keyframes offlinePulse { 0%,100% { opacity:1; } 50% { opacity:.55; } }

        .panel {

            min-width: 0;

            background:
                linear-gradient(
                    145deg,
                    rgba(15,23,35,.98),
                    rgba(10,17,27,.98)
                );

            border:
                1px solid var(--border);

            border-radius: 16px;

            padding: 21px;

            box-shadow:
                var(--shadow);
        }


        .panel-header {

            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 15px;

            margin-bottom: 20px;
        }


        .panel-title {

            color: var(--text);

            font-size: 14px;

            font-weight: 700;
        }


        .panel-subtitle {

            margin-top: 3px;

            color: var(--muted);

            font-size: 10px;
        }


        /* =====================================================
           DATE
        ===================================================== */

        .date-box {

            height: 36px;

            padding:
                0 10px;

            border:
                1px solid var(--border-light);

            border-radius: 8px;

            background:
                #0b121c;

            color:
                var(--text-soft);

            font-family:
                "Inter",
                sans-serif;

            font-size: 10px;

            outline: none;

            color-scheme: dark;

            cursor: pointer;
        }


        .date-box:focus {

            border-color:
                var(--blue);

            box-shadow:
                0 0 0 3px
                rgba(47,129,247,.10);
        }


        /* =====================================================
           CHART
        ===================================================== */

        .chart-wrapper {

            width: 100%;

            height: 350px;

            position: relative;
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status-box {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 13px;

            margin-top: 18px;

            border-radius: 12px;

            background:
                var(--green-soft);

            border:
                1px solid
                rgba(34,197,94,.20);
        }


        .status-icon {

            width: 35px;
            height: 35px;

            flex-shrink: 0;

            border-radius: 9px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                #16a34a;

            color: white;

            font-size: 15px;

            font-weight: 700;
        }


        .status-title {

            color:
                #86efac;

            font-size: 11px;

            font-weight: 700;
        }


        .status-text {

            color:
                #729080;

            margin-top: 2px;

            font-size: 9px;
        }


        /* =====================================================
           INFO
        ===================================================== */

        .info-grid {

            display: grid;

            grid-template-columns:
                repeat(2,minmax(0,1fr));

            gap: 10px;

            margin-top: 14px;
        }


        .info-item {

            min-width: 0;

            padding: 12px;

            border:
                1px solid var(--border);

            border-radius: 10px;

            background:
                rgba(7,12,19,.55);
        }


        .info-label {

            color:
                var(--muted);

            font-size: 9px;

            font-weight: 500;
        }


        .info-value {

            margin-top: 5px;

            color:
                var(--text-soft);

            font-size: 11px;

            font-weight: 600;

            overflow-wrap:
                anywhere;

            font-variant-numeric:
                tabular-nums;
        }


        /* =====================================================
           LOAD STATUS
        ===================================================== */

        #loadStatus {

            display: inline-flex;

            align-items: center;

            padding:
                3px 8px;

            border-radius:
                999px;

            background:
                var(--green-soft);

            color:
                #6ee7a0;

            font-size:
                9px;

            font-weight:
                600;
        }


        /* =====================================================
           TOKEN
        ===================================================== */

        .token-box {

            margin-top: 13px;

            padding: 15px;

            border-radius: 12px;

            background:
                linear-gradient(
                    145deg,
                    rgba(20,32,47,.65),
                    rgba(9,15,23,.8)
                );

            border:
                1px dashed
                #2b3b4e;
        }


        .token-label {

            color:
                var(--muted);

            font-size: 9px;

            font-weight: 700;

            letter-spacing:
                .3px;
        }


        .token-value {

            margin-top: 6px;

            color:
                var(--text);

            font-size: 18px;

            font-weight: 700;
        }


        .token-note {

            margin-top: 4px;

            color:
                var(--muted);

            font-size: 9px;

            line-height: 1.5;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {

            border-top:
                1px solid var(--border);

            padding:
                20px;

            text-align: center;

            color:
                #536276;

            font-size: 12px;
        }


        /* =====================================================
           TABLET / LAPTOP
        ===================================================== */

        @media (max-width: 1250px) {

            .cards {

                grid-template-columns:
                    repeat(3,minmax(0,1fr));
            }


            .main-grid {

                grid-template-columns:
                    minmax(0,1.5fr)
                    minmax(300px,1fr);
            }

        }


        @media (max-width: 950px) {
            .charts-grid { grid-template-columns: 1fr; }
            .system-panel { grid-column: auto; }

            .main-grid {

                grid-template-columns:
                    1fr;
            }


            .chart-wrapper {

                height: 330px;
            }

        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 650px) {

            .header {

                min-height: 66px;

                padding:
                    10px 13px;
            }


            .logo {

                width: 38px;
                height: 38px;

                border-radius: 10px;

                font-size: 19px;
            }


            .brand {

                gap: 9px;
            }


            .brand h1 {

                font-size: 13px;

                white-space:
                    normal;

                line-height:
                    1.2;
            }


            .brand p {

                font-size: 8px;
            }


            .connection {

                padding:
                    6px 8px;

                font-size: 8px;

                gap: 6px;
            }


            .dot {

                width: 6px;
                height: 6px;
            }


            .container {

                width:
                    calc(100% - 20px);

                padding:
                    21px 0 28px;
            }


            .welcome {

                margin-bottom: 16px;
            }


            .welcome h2 {

                font-size: 20px;
            }


            .welcome p {

                font-size: 10px;
            }


            /* 2 COLUMN MOBILE */

            .cards {

                grid-template-columns:
                    repeat(2,minmax(0,1fr));

                gap: 9px;

                margin-bottom: 12px;
            }


            .card {

                padding: 13px;

                border-radius: 12px;
            }


            .card-head {

                margin-bottom: 11px;
            }


            .card-title {

                font-size: 9px;
            }


            .metric-icon {

                width: 29px;
                height: 29px;

                border-radius: 8px;
            }


            .metric-icon svg {

                width: 15px;
                height: 15px;
            }


            .value {

                font-size: 21px;
            }


            .unit {

                font-size: 9px;
            }


            .panel {

                padding: 15px;

                border-radius: 13px;
            }


            .panel-header {

                flex-direction:
                    column;

                gap: 10px;

                margin-bottom: 12px;
            }


            .date-box {

                width: 100%;
            }


            .chart-wrapper {

                height: 260px;
            }


            .info-grid {

                grid-template-columns:
                    repeat(2,minmax(0,1fr));
            }

        }


        /* =====================================================
           VERY SMALL PHONE
        ===================================================== */

        @media (max-width: 380px) {

            .connection span:last-child {

                display: none;
            }


            .connection {

                width: 24px;
                height: 24px;

                padding: 0;

                justify-content:
                    center;
            }


            .cards {

                gap: 8px;
            }


            .card {

                padding: 11px;
            }


            .value {

                font-size: 19px;
            }


            .info-grid {

                grid-template-columns:
                    1fr;
            }

        }

    </style>

</head>


<body>


<!-- ==========================================================
     HEADER
=========================================================== -->

<header class="header">

    <div class="brand">

        <div class="logo">
    <img
        src="/images/logo-pln.png"
        alt="Logo PLN"
    >
</div>

        <div>

            <h1>
                MONITORING LISTRIK RUMAH F3
            </h1>

            <p>
                Smart Electrical Monitoring System
            </p>

        </div>

    </div>


    <div class="connection" id="connectionStatusBox">
    <span class="dot" id="connectionDot"></span>
    <span id="connectionStatus">System Online</span>

    <span class="connection-divider"></span>

    <span class="live-clock">
        <span id="liveClock">00:00:00</span>
        <span class="clock-zone">WIB</span>
    </span>
</div>

</header>



<!-- ==========================================================
     MAIN
=========================================================== -->

<main class="container">


    <div class="welcome">

        <h2>
            Dashboard Monitoring
        </h2>

        <p>
            Pemantauan kondisi listrik rumah secara real-time.
        </p>

    </div>



    <!-- ======================================================
         SENSOR CARDS
    ======================================================= -->

    <section class="cards">


        <!-- VOLTAGE -->

        <div class="card voltage-card">

            <div class="card-head">

                <div class="card-title">
                    Tegangan
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path
                            d="M13 2L4 14h6l-1 8 9-12h-6l1-8z"
                        />

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="voltage">
                    —
                </span>

                <span class="unit">
                    V
                </span>

            </div>

        </div>



        <!-- CURRENT -->

        <div class="card current-card">

            <div class="card-head">

                <div class="card-title">
                    Arus
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M12 2v20"/>

                        <path d="M7 7l5-5 5 5"/>

                        <path d="M7 17l5 5 5-5"/>

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="current">
                    —
                </span>

                <span class="unit">
                    A
                </span>

            </div>

        </div>



        <!-- POWER -->

        <div class="card power-card">

            <div class="card-head">

                <div class="card-title">
                    Daya Aktif
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path
                            d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"
                        />

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="power">
                    —
                </span>

                <span class="unit">
                    W
                </span>

            </div>

        </div>



        <!-- ENERGY -->

        <div class="card energy-card">

            <div class="card-head">

                <div class="card-title">
                    Energi
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <rect
                            x="3"
                            y="5"
                            width="16"
                            height="14"
                            rx="2"
                        />

                        <path d="M21 9v6"/>

                        <path
                            d="M10 8l-3 4h3l-1 4 4-5h-3l1-3z"
                        />

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="energy">
                    —
                </span>

                <span class="unit">
                    kWh
                </span>

            </div>

        </div>



        <!-- FREQUENCY -->

        <div class="card frequency-card">

            <div class="card-head">

                <div class="card-title">
                    Frekuensi
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path
                            d="M2 12h4l2-7 4 14 2-7h8"
                        />

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="frequency">
                    —
                </span>

                <span class="unit">
                    Hz
                </span>

            </div>

        </div>



        <!-- POWER FACTOR -->

        <div class="card pf-card">

            <div class="card-head">

                <div class="card-title">
                    Power Factor
                </div>

                <div class="metric-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path
                            d="M4 14a8 8 0 1 1 16 0"
                        />

                        <path
                            d="M12 14l4-5"
                        />

                        <circle
                            cx="12"
                            cy="14"
                            r="1"
                        />

                    </svg>

                </div>

            </div>


            <div class="metric-value">

                <span
                    class="value"
                    id="powerFactor">
                    —
                </span>

                <span class="unit">
                    PF
                </span>

            </div>

        </div>


    </section>



    <!-- ======================================================
         MAIN GRID
    ======================================================= -->

    <!-- ======================================================
         GRAFIK MONITORING
    ======================================================= -->
    <section class="charts-grid">
        <div class="panel chart-panel">
            <div class="panel-header"><div><div class="panel-title">Pemakaian Daya</div><div class="panel-subtitle">Rata-rata daya aktif setiap jam</div></div><input type="date" id="historyDate" class="date-box"></div>
            <div class="chart-wrapper"><canvas id="dailyChart"></canvas></div>
        </div>
        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Tegangan</div><div class="panel-subtitle">Rata-rata tegangan setiap jam</div></div></div><div class="chart-wrapper"><canvas id="voltageChart"></canvas></div></div>
        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Arus</div><div class="panel-subtitle">Rata-rata arus setiap jam</div></div></div><div class="chart-wrapper"><canvas id="currentChart"></canvas></div></div>
        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Energi</div><div class="panel-subtitle">Energi terukur dalam kWh</div></div></div><div class="chart-wrapper"><canvas id="energyChart"></canvas></div></div>
        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Frekuensi</div><div class="panel-subtitle">Rata-rata frekuensi setiap jam</div></div></div><div class="chart-wrapper"><canvas id="frequencyChart"></canvas></div></div>
        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Power Factor</div><div class="panel-subtitle">Rata-rata faktor daya setiap jam</div></div></div><div class="chart-wrapper"><canvas id="powerFactorChart"></canvas></div></div>

        <div class="panel system-panel">
            <div class="panel-title">Informasi Sistem</div>
            <div class="panel-subtitle">Kondisi monitoring saat ini</div>
            <div class="status-box" id="systemStatusBox">
                <div class="status-icon" id="systemStatusIcon">✓</div>
                <div><div class="status-title" id="systemStatusTitle">Sistem Monitoring Aktif</div><div class="status-text" id="systemStatusText">Data diperbarui dari perangkat monitoring.</div></div>
            </div>
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Device ID</div><div class="info-value" id="deviceId">—</div></div>
                <div class="info-item"><div class="info-label">Daya Semu</div><div class="info-value"><span id="apparentPower">—</span> VA</div></div>
                <div class="info-item"><div class="info-label">Pembacaan Terakhir</div><div class="info-value" id="lastUpdate">—</div></div>
                <div class="info-item"><div class="info-label">Status Beban</div><div class="info-value"><span id="loadStatus">—</span></div></div>
            </div>
            <div class="token-box"><div class="token-label">ESTIMASI TOKEN LISTRIK</div><div class="token-value">Belum diatur</div><div class="token-note">Fitur token akan terhubung setelah konfigurasi token listrik dilakukan.</div></div>
        </div>
    </section>

</main>



<!-- ==========================================================
     FOOTER
=========================================================== -->

<footer class="footer">

    PROYEK TEKNOLOGI INFORMASI © 2026

</footer>



<script>

    /* ========================================================
       GLOBAL
    ======================================================== */

    let dailyChart;
    let voltageChart;
    let currentChart;
    let energyChart;
    let frequencyChart;
    let powerFactorChart;

    const OFFLINE_TIMEOUT_MS = 15000;

    let historyRefreshTimer = null;


    /* ========================================================
       CONNECTION STATUS
    ======================================================== */

    function setConnectionUI(online) {

        const box = document.getElementById('connectionStatusBox');
        const dot = document.getElementById('connectionDot');
        const status = document.getElementById('connectionStatus');

        const sb = document.getElementById('systemStatusBox');
        const si = document.getElementById('systemStatusIcon');
        const st = document.getElementById('systemStatusTitle');
        const sx = document.getElementById('systemStatusText');

        if (online) {

            box.classList.remove('offline');

            dot.classList.remove('offline');
            dot.classList.add('online');

            status.textContent = 'System Online';

            sb.style.background = 'var(--green-soft)';
            sb.style.borderColor = 'rgba(34,197,94,.20)';

            si.style.background = '#16a34a';
            si.textContent = '✓';

            st.style.color = '#86efac';
            st.textContent = 'Sistem Monitoring Aktif';

            sx.textContent =
                'Data diperbarui dari perangkat monitoring.';

        } else {

            box.classList.add('offline');

            dot.classList.remove('online');
            dot.classList.add('offline');

            status.textContent = 'System Offline';

            sb.style.background = 'var(--red-soft)';
            sb.style.borderColor = 'rgba(239,68,68,.20)';

            si.style.background = '#dc2626';
            si.textContent = '×';

            st.style.color = '#fca5a5';
            st.textContent = 'ESP32 Tidak Terhubung';

            sx.textContent =
                'Tidak ada data baru dari perangkat monitoring.';
        }
    }


    function updateConnectionStatus(createdAt) {

        if (!createdAt) {

            setConnectionUI(false);

            return;
        }

        const lastSeen =
            new Date(createdAt).getTime();

        const age =
            Date.now() - lastSeen;

        setConnectionUI(
            Number.isFinite(lastSeen) &&
            age <= OFFLINE_TIMEOUT_MS
        );
    }


    /* ========================================================
       LATEST DATA
    ======================================================== */

    async function loadLatestData() {

        try {

            const response =
                await fetch(
                    '/api/readings/latest',
                    {
                        cache: 'no-store'
                    }
                );

            const result =
                await response.json();

            if (
                !result.success ||
                !result.data
            ) {

                updateConnectionStatus(null);

                return;
            }

            const data =
                result.data;


            /* =========================
               SENSOR CARD
            ========================= */

            document.getElementById('voltage')
                .textContent =
                data.voltage ?? '—';

            document.getElementById('current')
                .textContent =
                data.current ?? '—';

            document.getElementById('power')
                .textContent =
                data.power ?? '—';

            document.getElementById('energy')
                .textContent =
                data.energy ?? '—';

            document.getElementById('frequency')
                .textContent =
                data.frequency ?? '—';

            document.getElementById('powerFactor')
                .textContent =
                data.power_factor ?? '—';


            /* =========================
               SYSTEM INFORMATION
            ========================= */

            document.getElementById('apparentPower')
                .textContent =
                data.apparent_power ?? '—';

            document.getElementById('deviceId')
                .textContent =
                data.device_id ?? '—';


            /* =========================
               LAST UPDATE
            ========================= */

            if (data.created_at) {

                const d =
                    new Date(data.created_at);

                document.getElementById('lastUpdate')
                    .textContent =
                    d.toLocaleString(
                        'id-ID',
                        {
                            timeZone: 'Asia/Jakarta',
                            weekday: 'long',
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        }
                    ) + ' WIB';
            }


            /* =========================
               LOAD STATUS
            ========================= */

            const power =
                Number(data.power);

            const el =
                document.getElementById('loadStatus');

            if (!isNaN(power)) {

                if (power < 500) {

                    el.textContent = 'Normal';

                    el.style.background =
                        'rgba(34,197,94,.10)';

                    el.style.color =
                        '#6ee7a0';

                } else if (power < 1000) {

                    el.textContent = 'Tinggi';

                    el.style.background =
                        'rgba(245,158,11,.10)';

                    el.style.color =
                        '#fbbf24';

                } else {

                    el.textContent =
                        'Sangat Tinggi';

                    el.style.background =
                        'rgba(239,68,68,.10)';

                    el.style.color =
                        '#f87171';
                }
            }


            /* =========================
               CONNECTION
            ========================= */

            updateConnectionStatus(
                data.created_at
            );

        } catch (error) {

            console.error(
                'Gagal mengambil data terbaru:',
                error
            );
        }
    }


    /* ========================================================
       CHART CREATOR
       1 HARI = 1440 TITIK
    ======================================================== */

    function createLineChart(
        existing,
        id,
        label,
        values,
        yTitle,
        accent,
        unit
    ) {

        const ctx =
            document.getElementById(id);

        if (!ctx) {

            return existing;
        }


        if (existing) {

            existing.destroy();
        }


        return new Chart(
            ctx,
            {

                type: 'line',

                data: {

                    labels:
                        window.historyLabels,

                    datasets: [

                        {

                            label: label,

                            data: values,

                            borderColor: accent,

                            backgroundColor:
                                accent + '18',

                            borderWidth: 2.5,

                            tension: 0.35,

                            fill: true,

                            spanGaps: false,

                            /*
                             * Karena sekarang ada 1440 titik,
                             * titik tidak ditampilkan semuanya
                             * agar grafik tetap bersih.
                             */

                            pointRadius: 0,

                            pointHoverRadius: 6,

                            pointBackgroundColor:
                                accent,

                            pointBorderColor:
                                '#0f1723',

                            pointBorderWidth: 2
                        }

                    ]
                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    interaction: {

                        intersect: false,

                        mode: 'index'
                    },


                    plugins: {

                        legend: {

                            display: true,

                            position: 'top',

                            align: 'start',

                            labels: {

                                usePointStyle: true,

                                pointStyle: 'circle',

                                boxWidth: 7,

                                color: '#8998aa',

                                font: {

                                    family: 'Inter',

                                    size: 9,

                                    weight: '500'
                                }
                            }
                        },


                        tooltip: {

                            backgroundColor:
                                '#101a28',

                            borderColor:
                                '#263548',

                            borderWidth: 1,

                            titleColor:
                                '#f1f5f9',

                            bodyColor:
                                '#c5d0dc',

                            padding: 10,

                            displayColors: false,


                            callbacks: {

                                label: function(context) {

                                    if (
                                        context.raw === null ||
                                        context.raw === undefined
                                    ) {

                                        return 'Belum ada data';
                                    }


                                    return (
                                        label +
                                        ': ' +
                                        context.raw +
                                        ' ' +
                                        unit
                                    );
                                }
                            }
                        }
                    },


                    scales: {

                        y: {

                            beginAtZero: false,

                            border: {

                                display: false
                            },

                            grid: {

                                color:
                                    'rgba(116,131,152,.12)'
                            },

                            ticks: {

                                color:
                                    '#66758a',

                                font: {

                                    family: 'Inter',

                                    size: 9
                                }
                            },

                            title: {

                                display: true,

                                text: yTitle,

                                color:
                                    '#66758a',

                                font: {

                                    family: 'Inter',

                                    size: 9,

                                    weight: '500'
                                }
                            }
                        },


                        x: {

                            border: {

                                display: false
                            },

                            grid: {

                                display: false
                            },

                            ticks: {

                                color:
                                    '#66758a',

                                maxRotation: 0,

                                autoSkip: false,

                                font: {

                                    family: 'Inter',

                                    size: 8
                                },

                                /*
                                 * Data tetap 1440 titik,
                                 * tetapi label hanya ditampilkan
                                 * setiap 1 jam.
                                 */

                                callback: function(
                                    value,
                                    index
                                ) {

                                    if (
                                        index % 60 === 0
                                    ) {

                                        return this
                                            .getLabelForValue(
                                                value
                                            );
                                    }

                                    return '';
                                }
                            },

                            title: {

                                display: true,

                                text: 'Waktu',

                                color:
                                    '#66758a',

                                font: {

                                    family: 'Inter',

                                    size: 9,

                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            }
        );
    }


    /* ========================================================
       LOAD HISTORY
       1 MENIT / TITIK
       24 JAM = 1440 TITIK
    ======================================================== */

    async function loadDailyHistory() {

        const date =
            document.getElementById(
                'historyDate'
            ).value;

        if (!date) {

            return;
        }


        try {

            const response =
                await fetch(
                    `/api/readings/history?date=${date}`,
                    {
                        cache: 'no-store'
                    }
                );


            const result =
                await response.json();


            if (!result.success) {

                console.error(
                    'History gagal:',
                    result
                );

                return;
            }


            const data =
                result.data || [];


            /*
             * PENTING:
             *
             * Backend mengirim:
             *
             * "time":"12:51"
             *
             * bukan:
             *
             * "hour":"12:51"
             *
             */

            window.historyLabels =
                data.map(
                    item => item.time
                );


            /* =========================
               WATT
            ========================= */

            dailyChart =
                createLineChart(
                    dailyChart,
                    'dailyChart',
                    'Rata-rata Daya (W)',
                    data.map(
                        item =>
                            item.average_power
                    ),
                    'Daya (W)',
                    '#3b82f6',
                    'W'
                );


            /* =========================
               TEGANGAN
            ========================= */

            voltageChart =
                createLineChart(
                    voltageChart,
                    'voltageChart',
                    'Rata-rata Tegangan (V)',
                    data.map(
                        item =>
                            item.average_voltage
                    ),
                    'Tegangan (V)',
                    '#22d3ee',
                    'V'
                );


            /* =========================
               ARUS
            ========================= */

            currentChart =
                createLineChart(
                    currentChart,
                    'currentChart',
                    'Rata-rata Arus (A)',
                    data.map(
                        item =>
                            item.average_current
                    ),
                    'Arus (A)',
                    '#f59e0b',
                    'A'
                );


            /* =========================
               ENERGI
            ========================= */

            energyChart =
                createLineChart(
                    energyChart,
                    'energyChart',
                    'Energi (kWh)',
                    data.map(
                        item =>
                            item.average_energy
                    ),
                    'Energi (kWh)',
                    '#22c55e',
                    'kWh'
                );


            /* =========================
               FREKUENSI
            ========================= */

            frequencyChart =
                createLineChart(
                    frequencyChart,
                    'frequencyChart',
                    'Rata-rata Frekuensi (Hz)',
                    data.map(
                        item =>
                            item.average_frequency
                    ),
                    'Frekuensi (Hz)',
                    '#a855f7',
                    'Hz'
                );


            /* =========================
               POWER FACTOR
            ========================= */

            powerFactorChart =
                createLineChart(
                    powerFactorChart,
                    'powerFactorChart',
                    'Rata-rata Power Factor',
                    data.map(
                        item =>
                            item.average_power_factor
                    ),
                    'Power Factor',
                    '#ef4444',
                    ''
                );


            console.log(
                `History berhasil dimuat: ${data.length} titik`
            );

        } catch (error) {

            console.error(
                'Gagal mengambil history:',
                error
            );
        }
    }


    /* ========================================================
       TODAY
    ======================================================== */

    function setToday() {

        const parts =
            new Intl.DateTimeFormat(
                'en-CA',
                {
                    timeZone: 'Asia/Jakarta',

                    year: 'numeric',

                    month: '2-digit',

                    day: '2-digit'
                }
            ).formatToParts(
                new Date()
            );


        const get =
            type =>
                parts.find(
                    x => x.type === type
                )?.value;


        document.getElementById(
            'historyDate'
        ).value =
            `${get('year')}-${get('month')}-${get('day')}`;
    }


    /* ========================================================
       LIVE CLOCK
    ======================================================== */

    function updateLiveClock() {

    const el = document.getElementById('liveClock');

    if (!el) {
        return;
    }

    const now = new Date();

    const datePart =
        new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).format(now);

    const timePart =
        new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).format(now);

    el.textContent =
        `${datePart} - ${timePart}`;
}

    /* ========================================================
       AUTO REFRESH HISTORY
       SETIAP 1 MENIT
    ======================================================== */

    function startHistoryAutoRefresh() {

        /*
         * Hapus timer lama jika ada
         */

        if (historyRefreshTimer) {

            clearTimeout(
                historyRefreshTimer
            );
        }


        /*
         * Hitung waktu menuju
         * pergantian menit berikutnya.
         *
         * Contoh:
         * 12:59:43
         * → refresh sekitar 13:00:00
         */

        const now =
            new Date();


        const delay =
            (
                60 -
                now.getSeconds()
            ) * 1000 -
            now.getMilliseconds();


        historyRefreshTimer =
            setTimeout(
                async function() {

                    await loadDailyHistory();


                    /*
                     * Setelah refresh pertama,
                     * lanjut setiap 60 detik.
                     */

                    historyRefreshTimer =
                        setInterval(
                            loadDailyHistory,
                            60000
                        );

                },
                Math.max(
                    delay,
                    1000
                )
            );
    }


    /* ========================================================
       INITIAL LOAD
    ======================================================== */

    updateLiveClock();

    setInterval(
        updateLiveClock,
        1000
    );


    setToday();


    loadLatestData();


    loadDailyHistory();


    /*
     * Data kartu tetap diperbarui
     * setiap 1 detik.
     */

    setInterval(
        loadLatestData,
        1000
    );


    /*
     * Grafik diperbarui
     * setiap pergantian menit.
     */

    startHistoryAutoRefresh();


    /*
     * Jika tanggal dipilih manual,
     * langsung reload grafik.
     */

    document.getElementById(
        'historyDate'
    ).addEventListener(
        'change',
        function() {

            loadDailyHistory();
        }
    );

</script>


</body>
</html>