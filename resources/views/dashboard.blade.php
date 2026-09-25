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
           POWERFUL COLOR THEME
        ===================================================== */

        body::before {
            content: ""; position: fixed; inset: 0; pointer-events: none; z-index: -1;
            background: radial-gradient(circle at 8% 18%, rgba(59,130,246,.13), transparent 24%),
                        radial-gradient(circle at 92% 18%, rgba(168,85,247,.11), transparent 24%),
                        radial-gradient(circle at 50% 100%, rgba(34,211,238,.08), transparent 30%);
        }

        .header { background: linear-gradient(90deg, rgba(8,14,24,.98), rgba(12,20,34,.96), rgba(10,15,27,.98)); border-bottom-color: rgba(59,130,246,.30); box-shadow: 0 8px 30px rgba(0,0,0,.30), 0 1px 0 rgba(34,211,238,.08); }
        .brand h1 { background: linear-gradient(90deg,#fff,#93c5fd,#67e8f9); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .welcome h2 { background: linear-gradient(90deg,#f8fafc,#60a5fa,#22d3ee); -webkit-background-clip:text; background-clip:text; color:transparent; }

        .card { background: linear-gradient(145deg,rgba(18,31,49,.99),rgba(8,16,28,.99)); border-color: rgba(71,95,125,.48); box-shadow: 0 12px 34px rgba(0,0,0,.27), inset 0 1px 0 rgba(255,255,255,.025); }
        .card::after { content:""; position:absolute; left:0; right:0; bottom:0; height:3px; background:linear-gradient(90deg,var(--card-accent),transparent 86%); box-shadow:0 0 16px var(--card-accent); }
        .cards .card:nth-child(1){--card-accent:#22d3ee}.cards .card:nth-child(2){--card-accent:#f59e0b}.cards .card:nth-child(3){--card-accent:#3b82f6}.cards .card:nth-child(4){--card-accent:#a855f7}
        .cards .card:nth-child(1) .metric-icon{color:#22d3ee;background:rgba(34,211,238,.10);border-color:rgba(34,211,238,.24)}
        .cards .card:nth-child(2) .metric-icon{color:#f59e0b;background:rgba(245,158,11,.10);border-color:rgba(245,158,11,.24)}
        .cards .card:nth-child(3) .metric-icon{color:#3b82f6;background:rgba(59,130,246,.10);border-color:rgba(59,130,246,.24)}
        .cards .card:nth-child(4) .metric-icon{color:#a855f7;background:rgba(168,85,247,.10);border-color:rgba(168,85,247,.24)}

        .history-toolbar { background:linear-gradient(100deg,rgba(16,31,52,.99),rgba(15,24,41,.98),rgba(27,19,49,.97)); border-color:rgba(96,165,250,.30); box-shadow:0 12px 34px rgba(0,0,0,.27),inset 0 1px 0 rgba(255,255,255,.025); }
        .history-toolbar::before { content:""; width:4px; align-self:stretch; border-radius:10px; background:linear-gradient(180deg,#22d3ee,#3b82f6,#a855f7); box-shadow:0 0 18px rgba(59,130,246,.50); }
        .history-btn { background:rgba(7,14,25,.82); border-color:rgba(96,165,250,.30); }
        .window-label { background:linear-gradient(135deg,rgba(59,130,246,.18),rgba(168,85,247,.16)); border-color:rgba(96,165,250,.34); color:#bfdbfe; box-shadow:0 0 18px rgba(59,130,246,.10); }

        .charts-grid .chart-panel { position:relative; overflow:hidden; background:linear-gradient(145deg,rgba(16,27,44,.99),rgba(8,16,27,.99)); border-color:rgba(71,95,125,.46); box-shadow:0 12px 34px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.025); }
        .charts-grid .chart-panel::before { content:""; position:absolute; left:0; top:0; width:100%; height:2px; background:linear-gradient(90deg,var(--chart-accent),transparent 78%); box-shadow:0 0 18px var(--chart-accent); }
        .charts-grid .chart-panel:nth-child(2){--chart-accent:#3b82f6}.charts-grid .chart-panel:nth-child(3){--chart-accent:#22d3ee}.charts-grid .chart-panel:nth-child(4){--chart-accent:#f59e0b}.charts-grid .chart-panel:nth-child(5){--chart-accent:#a855f7}
        .charts-grid .chart-panel .panel-title::before { content:""; display:inline-block; width:7px; height:7px; margin-right:8px; vertical-align:1px; border-radius:50%; background:var(--chart-accent); box-shadow:0 0 10px var(--chart-accent); }
        .system-panel { background:linear-gradient(145deg,rgba(15,29,45,.99),rgba(10,18,30,.99)); border-color:rgba(34,211,238,.22); }

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

                repeat(4, minmax(0,1fr));


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


        


        .frequency-card {


            --accent: #a855f7;


            --icon-bg:

                rgba(168,85,247,.12);

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

        .history-toolbar {
            grid-column: 1 / -1;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 15px 17px;
            background: linear-gradient(145deg, rgba(17,28,42,.98), rgba(12,19,29,.98));
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow);
        }

        .history-info {
            min-width: 0;
        }

        .history-controls {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .date-navigation,
        .period-navigation {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .date-history-label {
            min-width: 142px;
            height: 36px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background: #0b121c;
            color: var(--text-soft);
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .window-label.live {
            color: #4ade80 !important;
            border-color: rgba(34,197,94,.45) !important;
            background: rgba(34,197,94,.07) !important;
            box-shadow: 0 0 12px rgba(34,197,94,.08) !important;
        }

        .history-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background: #0b121c;
            color: var(--text-soft);
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            transition: .2s ease;
        }

        .history-btn:hover:not(:disabled) {
            border-color: var(--blue);
            color: #fff;
            background: var(--blue-soft);
        }

        .history-btn:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .window-label {
            min-width: 112px;
            height: 36px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background: rgba(47,129,247,.08);
            color: #93c5fd;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
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

                    repeat(2,minmax(0,1fr));

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


            .history-toolbar {
                grid-column: auto;
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .history-controls {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .date-navigation,
            .period-navigation {
                width: 100%;
                display: grid;
                grid-template-columns: 36px minmax(0, 1fr) 36px;
                gap: 7px;
            }

            .date-navigation .history-btn,
            .period-navigation .history-btn {
                width: 36px;
            }

            .date-history-label,
            .window-label {
                min-width: 0;
                width: 100%;
                padding: 0 8px;
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


    

        /* =====================================================
           FINAL CLEAN COLOR FIX
           - Waktu LIVE hijau terang
           - Riwayat Monitoring rata kiri
           - Angka setiap sensor berbeda warna
           - Tetap clean, tidak terlalu ramai
        ===================================================== */

        .history-toolbar {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 18px;
        }

        .history-toolbar::before {
            position: absolute !important;
            left: 18px;
            top: 16px;
            bottom: 16px;
            width: 4px;
            height: auto;
        }

        .history-info {
            flex: 1 1 auto;
            min-width: 0;
            margin-left: 15px;
            text-align: left !important;
        }

        .history-info .panel-title,
        .history-info h3,
        .history-info h4,
        .history-info strong {
            text-align: left !important;
        }

        .history-info .panel-title {
            color: #f1f5f9;
        }

        .history-info .panel-subtitle {
            color: #7f96b2;
        }

        .history-controls {
            margin-left: auto;
            flex-shrink: 0;
        }

        /* WAKTU PERIODE / LIVE */
        .window-label {
            color: #39ff72 !important;
            border-color: rgba(57,255,114,.28) !important;
            background: rgba(20,70,38,.18) !important;
            box-shadow: 0 0 12px rgba(57,255,114,.10) !important;
            font-weight: 700 !important;
        }

        /* ANGKA SENSOR — WARNA BERBEDA */
        .voltage-card .value {
            color: #22d3ee !important;
            text-shadow: 0 0 12px rgba(34,211,238,.20);
        }

        .current-card .value {
            color: #fbbf24 !important;
            text-shadow: 0 0 12px rgba(251,191,36,.18);
        }

        .power-card .value {
            color: #fb7185 !important;
            text-shadow: 0 0 12px rgba(251,113,133,.18);
        }

        .frequency-card .value {
            color: #c084fc !important;
            text-shadow: 0 0 12px rgba(192,132,252,.18);
        }

        /* UNIT MENGIKUTI WARNA ANGKA, TAPI LEBIH SOFT */
        .voltage-card .unit { color: rgba(34,211,238,.72) !important; }
        .current-card .unit { color: rgba(251,191,36,.72) !important; }
        .power-card .unit { color: rgba(251,113,133,.72) !important; }
        .frequency-card .unit { color: rgba(192,132,252,.72) !important; }

        /* Jangan terlalu ramai: glow hanya halus */
        .card:hover {
            box-shadow: 0 12px 35px rgba(0,0,0,.30);
        }

        @media (max-width: 700px) {
            .history-toolbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .history-info {
                width: 100%;
                margin-left: 15px;
            }

            .history-controls {
                width: 100%;
                justify-content: flex-end;
                margin-left: 0;
            }
        }


        /* =====================================================
           PROFESSIONAL UI OVERRIDE
           Clean • Corporate • Industrial • Minimal glow
        ===================================================== */

        :root {
            --pro-bg: #070c13;
            --pro-panel: #0d1621;
            --pro-panel-2: #101b28;
            --pro-border: #1c2a3a;
            --pro-border-soft: #243447;
            --pro-text: #f3f6fa;
            --pro-muted: #7f8da0;
            --pro-voltage: #29c7e8;
            --pro-current: #f4b740;
            --pro-power: #f06a7a;
            --pro-frequency: #a979e8;
            --pro-live: #49e878;
        }

        html, body {
            background: var(--pro-bg) !important;
        }

        body {
            background:
                linear-gradient(180deg, #080d14 0%, #070c13 100%) !important;
            color: var(--pro-text);
        }

        /* Header: simple, premium, no AI-style gradient */
        .header {
            background: #0a111a !important;
            border-bottom: 1px solid #1b2938 !important;
            box-shadow: 0 4px 18px rgba(0,0,0,.18) !important;
        }

        .brand h1 {
            background: none !important;
            color: #edf3f8 !important;
        }

        .brand h1::first-letter {
            color: inherit;
        }

        .welcome h2 {
            background: none !important;
            color: #edf3f8 !important;
        }

        .welcome p {
            color: #8291a4 !important;
        }

        /* Sensor cards */
        .card {
            background: #0d1621 !important;
            border: 1px solid var(--pro-border) !important;
            border-top: 2px solid var(--accent) !important;
            border-radius: 12px !important;
            box-shadow: 0 7px 22px rgba(0,0,0,.20) !important;
        }

        .card:hover {
            transform: translateY(-1px) !important;
            border-color: var(--pro-border-soft) !important;
            box-shadow: 0 9px 26px rgba(0,0,0,.25) !important;
        }

        .card::after {
            display: none !important;
        }

        .card-title {
            color: #8291a4 !important;
            letter-spacing: .45px !important;
        }

        .metric-icon {
            background: rgba(255,255,255,.035) !important;
            border: 1px solid rgba(255,255,255,.07) !important;
            box-shadow: none !important;
        }

        /* Angka dibuat berbeda, tetapi tetap elegan */
        .voltage-card .value {
            color: var(--pro-voltage) !important;
            text-shadow: none !important;
        }

        .current-card .value {
            color: var(--pro-current) !important;
            text-shadow: none !important;
        }

        .power-card .value {
            color: var(--pro-power) !important;
            text-shadow: none !important;
        }

        .frequency-card .value {
            color: var(--pro-frequency) !important;
            text-shadow: none !important;
        }

        .voltage-card .unit { color: rgba(41,199,232,.75) !important; }
        .current-card .unit { color: rgba(244,183,64,.75) !important; }
        .power-card .unit { color: rgba(240,106,122,.75) !important; }
        .frequency-card .unit { color: rgba(169,121,232,.75) !important; }

        .voltage-card .metric-icon { color: var(--pro-voltage) !important; }
        .current-card .metric-icon { color: var(--pro-current) !important; }
        .power-card .metric-icon { color: var(--pro-power) !important; }
        .frequency-card .metric-icon { color: var(--pro-frequency) !important; }

        /* Riwayat Monitoring: rata kiri dan lebih seperti dashboard industri */
        .history-toolbar {
            background: #0d1621 !important;
            border: 1px solid var(--pro-border) !important;
            border-radius: 12px !important;
            box-shadow: 0 7px 22px rgba(0,0,0,.18) !important;
            padding: 15px 16px !important;
            justify-content: flex-start !important;
            gap: 16px !important;
        }

        .history-toolbar::before {
            content: "" !important;
            position: static !important;
            flex: 0 0 3px !important;
            width: 3px !important;
            height: 42px !important;
            align-self: center !important;
            border-radius: 3px !important;
            background: var(--pro-live) !important;
            box-shadow: 0 0 8px rgba(73,232,120,.22) !important;
        }

        .history-info {
            margin-left: 0 !important;
            text-align: left !important;
            flex: 1 1 auto !important;
        }

        .history-info .panel-title {
            color: #eaf1f6 !important;
            text-align: left !important;
            font-size: 15px !important;
            font-weight: 700 !important;
        }

        .history-info .panel-subtitle {
            color: #738297 !important;
            text-align: left !important;
            margin-top: 3px !important;
        }

        .history-controls {
            margin-left: auto !important;
        }

        .history-btn {
            background: #0a121c !important;
            border: 1px solid #263548 !important;
            color: #b9c5d1 !important;
            box-shadow: none !important;
        }

        .history-btn:hover:not(:disabled) {
            background: #111c29 !important;
            border-color: #3a4b60 !important;
        }

        /* Periode LIVE: satu-satunya elemen yang boleh benar-benar menyala */
        .window-label {
            background: rgba(73,232,120,.055) !important;
            border: 1px solid rgba(73,232,120,.45) !important;
            color: var(--pro-live) !important;
            box-shadow: 0 0 10px rgba(73,232,120,.10) !important;
            font-weight: 700 !important;
            letter-spacing: .15px !important;
        }

        /* Chart panels */
        .charts-grid .chart-panel {
            background: #0d1621 !important;
            border: 1px solid var(--pro-border) !important;
            border-radius: 12px !important;
            box-shadow: 0 7px 22px rgba(0,0,0,.18) !important;
        }

        .charts-grid .chart-panel::before {
            height: 2px !important;
            background: var(--chart-accent) !important;
            box-shadow: none !important;
        }

        .charts-grid .chart-panel .panel-title::before {
            width: 6px !important;
            height: 6px !important;
            box-shadow: none !important;
        }

        .panel-subtitle {
            color: #718096 !important;
        }

        .system-panel {
            background: #0d1621 !important;
            border: 1px solid var(--pro-border) !important;
            box-shadow: 0 7px 22px rgba(0,0,0,.18) !important;
        }

        /* Header clock tetap informatif, tidak dibuat neon */
        .live-clock {
            color: #d8e0e8 !important;
        }

        .clock-zone {
            color: #6fa7d7 !important;
        }

        @media (max-width: 700px) {
            .history-toolbar::before {
                align-self: flex-start !important;
                margin-top: 2px !important;
            }
        }



        /* =====================================================
           FINAL RESPONSIVE FIX
           Desktop • Laptop • Tablet • Mobile
           ===================================================== */

        html {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            width: 100%;
            overflow-x: hidden;
        }

        .container {
            width: min(calc(100% - 64px), 1680px);
            max-width: 1680px;
            margin-left: auto;
            margin-right: auto;
        }

        .brand,
        .history-info,
        .history-controls,
        .chart-panel,
        .system-panel {
            min-width: 0;
        }

        .chart-wrapper {
            width: 100%;
            min-width: 0;
            position: relative;
        }

        .chart-wrapper canvas {
            display: block !important;
            width: 100% !important;
            max-width: 100%;
        }

/* Container Card Utama (Sesuaikan dengan warna card gambar) */
.custom-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  
  /* Warna background card & border persis seperti di gambar */
  background-color: #0d1621 !important; 
  border: 1px solid #1e293b;
  border-radius: 14px;
  padding: 16px 20px;
  gap: 16px;
  box-sizing: border-box;
}

/* Sisi Kiri Layout */
.header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}

/* Garis Aksen Hijau Vertikal */
.accent-line {
  width: 4px;
  height: 38px;
  background-color: #10b981;
  border-radius: 4px;
  flex-shrink: 0;
}

/* Teks Judul & Subtitle */
.header-text h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #ffffff;
}

.header-text p {
  margin: 4px 0 0 0;
  font-size: 13px;
  color: #64748b;
}

/* Sisi Kanan Controls */
.header-controls {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.nav-group {
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Tombol Panah < > */
.btn-icon {
  background-color: #080e1a; /* Dibuat sedikit lebih gelap dari card agar recessed */
  border: 1px solid #1e293b;
  color: #94a3b8;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-icon:hover {
  background-color: #1e293b;
  color: #ffffff;
}

/* Badge Kotak Tanggal */
.badge-box {
  background-color: #080e1a;
  border: 1px solid #1e293b;
  color: #ffffff;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  white-space: nowrap;
}

/* Badge Periode LIVE */
.badge-live {
  border-color: #059669;
  color: #10b981;
  background-color: rgba(16, 185, 129, 0.08);
}

        /* LAPTOP */
        @media (max-width: 1100px) {
            .container {
                width: calc(100% - 48px);
                max-width: 1680px;
                margin-left: auto;
                margin-right: auto;
                padding-top: 26px;
            }

            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main-grid {
                grid-template-columns: 1fr;
            }

            .history-toolbar {
                grid-column: 1 / -1;
            }
        }

        /* TABLET */
        @media (max-width: 800px) {
            .header {
                position: relative;
                padding: 12px 18px;
                gap: 12px;
            }

            .brand {
                flex: 1 1 auto;
            }

            .connection {
                flex-shrink: 1;
                max-width: 48%;
            }

            .live-clock {
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .container {
                width: calc(100% - 40px);
                max-width: none;
                margin-left: auto;
                margin-right: auto;
                padding: 24px 0 30px;
            }

            .welcome {
                margin-bottom: 18px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                height: 300px;
            }

            .history-toolbar {
                width: 100%;
                flex-wrap: nowrap;
            }

            .history-controls {
                flex-shrink: 0;
            }
        }

        /* PHONE */
        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: stretch;
                padding: 11px 14px;
            }

            .brand {
                width: 100%;
            }

            .logo {
                width: 40px;
                height: 40px;
            }

            .brand h1 {
                font-size: 14px;
                white-space: normal;
                line-height: 1.25;
            }

            .brand p {
                font-size: 8.5px;
            }

            .connection {
                width: 100%;
                max-width: none;
                justify-content: center;
                padding: 7px 10px;
                border-radius: 9px;
            }

            .live-clock {
                font-size: 11px;
                justify-content: center;
            }

            .clock-zone {
                font-size: 10px;
            }

            .container {
                width: calc(100% - 28px);
                max-width: none;
                margin-left: auto;
                margin-right: auto;
                padding: 20px 0 26px;
            }

            .welcome h2 {
                font-size: 21px;
            }

            .welcome p {
                font-size: 10px;
            }

            /* Dua kartu per baris masih nyaman di HP */
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 14px;
            }

            .card {
                padding: 13px;
                border-radius: 11px;
            }

            .card-head {
                margin-bottom: 12px;
            }

            .card-title {
                font-size: 9px;
            }

            .metric-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
            }

            .metric-icon svg {
                width: 15px;
                height: 15px;
            }

            .value {
                font-size: clamp(22px, 7vw, 28px);
            }

            .unit {
                font-size: 9px;
            }

            /* Timeline HP: info tetap kiri, kontrol turun ke bawah */
            .history-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 14px;
                border-radius: 11px !important;
            }

            .history-toolbar::before {
                display: none !important;
            }

            .history-info {
                width: 100%;
                margin: 0 !important;
            }

            .history-info .panel-title {
                font-size: 14px !important;
            }

            .history-info .panel-subtitle {
                font-size: 9.5px;
                line-height: 1.45;
            }

            .history-controls {
                width: 100%;
                display: grid;
                grid-template-columns: 40px minmax(0, 1fr) 40px;
                gap: 8px;
                margin: 0 !important;
            }

            .history-btn {
                width: 40px;
                height: 38px;
                font-size: 21px;
            }

            .window-label {
                min-width: 0 !important;
                width: 100%;
                height: 38px;
                padding: 0 7px;
                font-size: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                white-space: nowrap;
            }

            .chart-panel,
            .system-panel {
                border-radius: 11px !important;
            }

            .chart-wrapper {
                height: 255px;
            }

            .panel-title {
                font-size: 14px;
            }

            .panel-subtitle {
                font-size: 9.5px;
            }
        }

        /* SMALL PHONE */
        @media (max-width: 430px) {
            .container {
                width: calc(100% - 24px);
                max-width: none;
                margin-left: auto;
                margin-right: auto;
                padding-top: 17px;
            }

            .cards {
                gap: 8px;
            }

            .card {
                padding: 11px;
            }

            .card-title {
                font-size: 8px;
            }

            .metric-icon {
                width: 27px;
                height: 27px;
            }

            .value {
                font-size: 21px;
                letter-spacing: -.7px;
            }

            .unit {
                font-size: 8px;
            }

            .history-info .panel-title {
                font-size: 13px !important;
            }

            .history-info .panel-subtitle {
                font-size: 9px;
            }

            .chart-wrapper {
                height: 225px;
            }
        }

        /* EXTRA SMALL PHONE */
        @media (max-width: 360px) {
            .header {
                padding-left: 10px;
                padding-right: 10px;
            }

            .brand h1 {
                font-size: 12px;
            }

            .brand p {
                display: none;
            }

            .live-clock {
                font-size: 9px;
            }

            .connection-divider,
            .clock-zone {
                display: none !important;
            }

            .container {
                width: calc(100% - 20px);
                max-width: none;
                margin-left: auto;
                margin-right: auto;
            }

            .cards {
                gap: 7px;
            }

            .card {
                padding: 9px;
            }

            .card-head {
                margin-bottom: 9px;
            }

            .metric-icon {
                width: 25px;
                height: 25px;
            }

            .value {
                font-size: 19px;
            }

            .history-controls {
                grid-template-columns: 36px minmax(0, 1fr) 36px;
            }

            .history-btn {
                width: 36px;
                height: 36px;
            }

            .window-label {
                height: 36px;
                font-size: 9px;
            }

            .chart-wrapper {
                height: 210px;
            }
        }



        /* =====================================================
           MOBILE HISTORY NAVIGATION FINAL FIX
           Date navigation dan period navigation dibuat 2 baris
           agar tidak saling menyempit di layar HP.
        ===================================================== */
        @media (max-width: 700px) {
            .history-toolbar {
                width: 100% !important;
                box-sizing: border-box !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 12px !important;
                padding: 14px !important;
            }

            .history-info {
                width: 100% !important;
                flex: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .history-controls {
                width: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 8px !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .date-navigation,
            .period-navigation {
                width: 100% !important;
                min-width: 0 !important;
                display: grid !important;
                grid-template-columns: 38px minmax(0, 1fr) 38px !important;
                align-items: center !important;
                gap: 7px !important;
            }

            .date-history-label,
            .window-label {
                width: 100% !important;
                min-width: 0 !important;
                max-width: none !important;
                box-sizing: border-box !important;
                height: 38px !important;
                padding: 0 8px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                font-size: 10px !important;
            }

            .history-btn {
                width: 38px !important;
                min-width: 38px !important;
                height: 38px !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
        }

        @media (max-width: 430px) {
            .history-toolbar {
                gap: 10px !important;
                padding: 12px !important;
            }

            .history-info .panel-title {
                font-size: 13px !important;
            }

            .history-info .panel-subtitle {
                font-size: 8.5px !important;
                line-height: 1.4 !important;
            }

            .date-navigation,
            .period-navigation {
                grid-template-columns: 36px minmax(0, 1fr) 36px !important;
                gap: 6px !important;
            }

            .history-btn {
                width: 36px !important;
                min-width: 36px !important;
                height: 36px !important;
                font-size: 19px !important;
            }

            .date-history-label,
            .window-label {
                height: 36px !important;
                font-size: 9.5px !important;
                padding: 0 6px !important;
            }
        }

        @media (max-width: 360px) {
            .history-toolbar {
                padding: 10px !important;
            }

            .date-navigation,
            .period-navigation {
                grid-template-columns: 34px minmax(0, 1fr) 34px !important;
                gap: 5px !important;
            }

            .history-btn {
                width: 34px !important;
                min-width: 34px !important;
                height: 34px !important;
                font-size: 18px !important;
            }

            .date-history-label,
            .window-label {
                height: 34px !important;
                font-size: 9px !important;
            }
        }


        /* =====================================================
           FINAL MOBILE HEADER + SYSTEM INFO FIX
           Hanya merapikan tampilan HP.
           Desktop/tablet dan fungsi lainnya tidak diubah.
        ===================================================== */

        @media (max-width: 600px) {

            /* ---------- HEADER ---------- */

            .header {
                width: 100% !important;
                min-width: 0 !important;
                flex-direction: column !important;
                align-items: stretch !important;
                justify-content: center !important;
                gap: 9px !important;
                padding: 10px 12px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .brand {
                width: 100% !important;
                min-width: 0 !important;
                flex: none !important;
                display: flex !important;
                align-items: center !important;
                gap: 9px !important;
                overflow: hidden !important;
            }

            .logo {
                width: 39px !important;
                height: 39px !important;
                flex: 0 0 39px !important;
                padding: 4px !important;
                border-radius: 9px !important;
            }

            .brand > div:last-child {
                min-width: 0 !important;
                flex: 1 1 auto !important;
                overflow: hidden !important;
            }

            .brand h1 {
                width: 100% !important;
                min-width: 0 !important;
                font-size: 13.5px !important;
                line-height: 1.2 !important;
                letter-spacing: -.2px !important;
                white-space: normal !important;
                overflow-wrap: break-word !important;
            }

            .brand p {
                max-width: 100% !important;
                font-size: 8px !important;
                line-height: 1.3 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* Kotak status dibuat 2 baris:
               status di atas, tanggal/jam di bawah. */
            .connection {
                width: 100% !important;
                max-width: none !important;
                min-width: 0 !important;
                display: grid !important;
                grid-template-columns: 8px minmax(0, 1fr) !important;
                grid-template-rows: auto auto !important;
                align-items: center !important;
                column-gap: 7px !important;
                row-gap: 4px !important;
                padding: 7px 10px !important;
                border-radius: 9px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .connection .dot {
                grid-column: 1 !important;
                grid-row: 1 !important;
                width: 7px !important;
                height: 7px !important;
                margin: 0 !important;
                justify-self: center !important;
            }

            .connection #connectionStatus {
                grid-column: 2 !important;
                grid-row: 1 !important;
                min-width: 0 !important;
                max-width: 100% !important;
                font-size: 9.5px !important;
                line-height: 1.2 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .connection .connection-divider {
                display: none !important;
            }

            .connection .live-clock {
    grid-column: 2 !important;
    grid-row: 2 !important;
    width: 100% !important;
    min-width: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 6px !important;
    font-size: 13px !important;
    line-height: 1.35 !important;
    font-weight: 700 !important;
    text-align: left !important;
    white-space: normal !important;
    overflow: hidden !important;
    font-variant-numeric: tabular-nums !important;
}

            .connection .live-clock #liveClock {
                min-width: 0 !important;
                max-width: 100% !important;
                white-space: normal !important;
                overflow-wrap: anywhere !important;
                overflow: hidden !important;
                text-overflow: clip !important;
            }

            .connection .clock-zone {
                flex: 0 0 auto !important;
                font-size: 8px !important;
                line-height: 1 !important;
                white-space: nowrap !important;
            }

            /* ---------- INFORMASI SISTEM ---------- */

            .system-panel {
                width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                padding: 14px !important;
            }

            .system-panel > .panel-title {
                font-size: 14px !important;
                line-height: 1.25 !important;
            }

            .system-panel > .panel-subtitle {
                font-size: 9px !important;
                line-height: 1.4 !important;
                margin-top: 2px !important;
            }

            .status-box {
                width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                display: flex !important;
                align-items: center !important;
                gap: 9px !important;
                padding: 10px !important;
                margin-top: 12px !important;
                border-radius: 10px !important;
                overflow: hidden !important;
            }

            .status-icon {
                width: 30px !important;
                height: 30px !important;
                flex: 0 0 30px !important;
                border-radius: 8px !important;
                font-size: 13px !important;
            }

            .status-box > div:last-child {
                min-width: 0 !important;
                flex: 1 1 auto !important;
                overflow: hidden !important;
            }

            .status-title {
                font-size: 10px !important;
                line-height: 1.25 !important;
                white-space: normal !important;
            }

            .status-text {
                font-size: 8px !important;
                line-height: 1.35 !important;
                white-space: normal !important;
            }

            .info-grid {
                width: 100% !important;
                min-width: 0 !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 7px !important;
                margin-top: 10px !important;
            }

            .info-item {
                width: 100% !important;
                min-width: 0 !important;
                padding: 9px !important;
                border-radius: 8px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .info-label {
                min-width: 0 !important;
                font-size: 8px !important;
                line-height: 1.3 !important;
                white-space: normal !important;
            }

            .info-value {
                width: 100% !important;
                min-width: 0 !important;
                margin-top: 4px !important;
                font-size: 10px !important;
                line-height: 1.35 !important;
                font-weight: 600 !important;
                overflow-wrap: anywhere !important;
                word-break: break-word !important;
                font-variant-numeric: tabular-nums !important;
            }

            /* Pembacaan terakhir dibuat sedikit lebih kecil
               supaya tanggal + jam tetap terlihat rapi. */
            .info-item:nth-child(3) .info-value {
                font-size: 8.8px !important;
                line-height: 1.35 !important;
                letter-spacing: -.1px !important;
            }

            .info-item:nth-child(4) .info-value {
                min-height: 27px !important;
                display: flex !important;
                align-items: center !important;
            }

            #loadStatus {
                max-width: 100% !important;
                box-sizing: border-box !important;
                padding: 3px 7px !important;
                font-size: 8px !important;
                line-height: 1.2 !important;
                white-space: nowrap !important;
            }

            .token-box {
                width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                padding: 11px !important;
                margin-top: 10px !important;
                overflow: hidden !important;
            }

            .token-label {
                font-size: 8px !important;
                line-height: 1.3 !important;
            }

            .token-value {
                font-size: 16px !important;
                line-height: 1.25 !important;
            }

            .token-note {
                font-size: 8px !important;
                line-height: 1.4 !important;
            }
        }

        @media (max-width: 430px) {

            .header {
                padding: 9px 10px !important;
                gap: 8px !important;
            }

            .logo {
                width: 37px !important;
                height: 37px !important;
                flex-basis: 37px !important;
            }

            .brand {
                gap: 8px !important;
            }

            .brand h1 {
                font-size: 13px !important;
            }

            .brand p {
                font-size: 7.5px !important;
            }

            .connection {
                padding: 6px 8px !important;
                column-gap: 6px !important;
                row-gap: 3px !important;
            }

            .connection #connectionStatus {
                font-size: 9px !important;
            }

            .connection .live-clock {
                font-size: 8.5px !important;
                line-height: 1.25 !important;
            }

            .connection .clock-zone {
                font-size: 7.5px !important;
            }

            .system-panel {
                padding: 12px !important;
            }

            .info-grid {
                gap: 6px !important;
            }

            .info-item {
                padding: 8px !important;
            }

            .info-label {
                font-size: 7.5px !important;
            }

            .info-value {
                font-size: 9.5px !important;
            }

            .info-item:nth-child(3) .info-value {
                font-size: 8.3px !important;
            }

            #loadStatus {
                font-size: 7.8px !important;
                padding: 3px 6px !important;
            }
        }

        @media (max-width: 360px) {

            .header {
                padding: 8px 9px !important;
            }

            .logo {
                width: 35px !important;
                height: 35px !important;
                flex-basis: 35px !important;
            }

            .brand h1 {
                font-size: 12px !important;
            }

            .connection {
                padding: 6px 7px !important;
            }

            .connection #connectionStatus {
                font-size: 8.5px !important;
            }

            .connection .live-clock {
                font-size: 8px !important;
            }

            .connection .clock-zone {
                font-size: 7px !important;
            }

            .system-panel {
                padding: 11px !important;
            }

            .info-grid {
                gap: 5px !important;
            }

            .info-item {
                padding: 7px !important;
            }

            .info-label {
                font-size: 7px !important;
            }

            .info-value {
                font-size: 9px !important;
            }

            .info-item:nth-child(3) .info-value {
                font-size: 8px !important;
            }
        }


        /* =====================================================
           MOBILE HEADER V2
           Lebih besar, lebih rapi, tetap aman di layar HP
        ===================================================== */

        @media (max-width: 900px) {

            .header {
                width: 100% !important;
                min-width: 0 !important;
                padding: 13px 16px 14px !important;
                gap: 12px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .brand {
                width: 100% !important;
                min-width: 0 !important;
                gap: 11px !important;
                align-items: center !important;
                overflow: hidden !important;
            }

            .logo {
                width: 46px !important;
                height: 46px !important;
                flex: 0 0 46px !important;
                border-radius: 10px !important;
            }

            .brand > div:last-child {
                min-width: 0 !important;
                flex: 1 1 auto !important;
                overflow: hidden !important;
            }

            .brand h1 {
                width: 100% !important;
                min-width: 0 !important;
                margin: 0 !important;
                font-size: 17px !important;
                line-height: 1.18 !important;
                letter-spacing: -.25px !important;
                white-space: normal !important;
                overflow-wrap: break-word !important;
            }

            .brand p {
                width: 100% !important;
                margin-top: 4px !important;
                font-size: 10px !important;
                line-height: 1.3 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* STATUS ONLINE */
            .connection {
                width: 100% !important;
                min-width: 0 !important;
                max-width: none !important;
                display: grid !important;
                grid-template-columns: 11px minmax(0, 1fr) !important;
                grid-template-rows: auto auto !important;
                align-items: center !important;
                column-gap: 10px !important;
                row-gap: 7px !important;
                padding: 11px 14px !important;
                border-radius: 14px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .connection .dot {
                grid-column: 1 !important;
                grid-row: 1 !important;
                width: 10px !important;
                height: 10px !important;
                margin: 0 !important;
                justify-self: center !important;
            }

            .connection #connectionStatus {
                grid-column: 2 !important;
                grid-row: 1 !important;
                min-width: 0 !important;
                font-size: 14px !important;
                line-height: 1.25 !important;
                font-weight: 700 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .connection .connection-divider {
                display: none !important;
            }

            .connection .live-clock {
                grid-column: 1 / -1 !important;
                grid-row: 2 !important;
                width: 100% !important;
                min-width: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
                font-size: 13px !important;
                line-height: 1.35 !important;
                font-weight: 700 !important;
                text-align: center !important;
                white-space: normal !important;
                overflow: hidden !important;
                font-variant-numeric: tabular-nums !important;
            }

            .connection .live-clock-icon {
                font-size: 13px !important;
                flex: 0 0 auto !important;
            }

            .connection .live-clock #liveClock {
                min-width: 0 !important;
                max-width: 100% !important;
                white-space: normal !important;
                overflow-wrap: break-word !important;
                word-break: normal !important;
                text-overflow: clip !important;
            }

            .connection .clock-zone {
                flex: 0 0 auto !important;
                font-size: 11px !important;
                line-height: 1.1 !important;
                white-space: nowrap !important;
            }
        }

        @media (max-width: 600px) {

            .header {
                padding: 12px 14px 13px !important;
                gap: 11px !important;
            }

            .logo {
                width: 44px !important;
                height: 44px !important;
                flex-basis: 44px !important;
            }

            .brand {
                gap: 10px !important;
            }

            .brand h1 {
                font-size: 16px !important;
                line-height: 1.2 !important;
            }

            .brand p {
                font-size: 9px !important;
                margin-top: 3px !important;
            }

            .connection {
                padding: 10px 12px !important;
                column-gap: 9px !important;
                row-gap: 6px !important;
                border-radius: 13px !important;
            }

            .connection .dot {
                width: 9px !important;
                height: 9px !important;
            }

            .connection #connectionStatus {
                font-size: 13px !important;
            }

            .connection .live-clock {
                font-size: 12px !important;
                gap: 5px !important;
                line-height: 1.35 !important;
            }

            .connection .clock-zone {
                font-size: 10px !important;
            }
        }

        @media (max-width: 430px) {

            .header {
                padding: 11px 12px 12px !important;
                gap: 10px !important;
            }

            .logo {
                width: 42px !important;
                height: 42px !important;
                flex-basis: 42px !important;
                border-radius: 9px !important;
            }

            .brand {
                gap: 9px !important;
            }

            .brand h1 {
                font-size: 15px !important;
            }

            .brand p {
                font-size: 8.5px !important;
            }

            .connection {
                padding: 10px 11px !important;
                column-gap: 8px !important;
                row-gap: 5px !important;
                border-radius: 12px !important;
            }

            .connection #connectionStatus {
                font-size: 12.5px !important;
            }

            .connection .live-clock {
                font-size: 11.5px !important;
                gap: 5px !important;
            }

            .connection .clock-zone {
                font-size: 9.5px !important;
            }
        }

        @media (max-width: 360px) {

            .header {
                padding: 10px 10px 11px !important;
            }

            .logo {
                width: 39px !important;
                height: 39px !important;
                flex-basis: 39px !important;
            }

            .brand h1 {
                font-size: 14px !important;
            }

            .brand p {
                font-size: 8px !important;
            }

            .connection {
                padding: 9px 10px !important;
            }

            .connection #connectionStatus {
                font-size: 11.5px !important;
            }

            .connection .live-clock {
                font-size: 10.5px !important;
            }

            .connection .clock-zone {
                font-size: 9px !important;
            }
        }


        /* =====================================================
           AI PREDICTION SECTION
        ===================================================== */

        .ai-section {
            margin-top: 28px;
        }

        .ai-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .ai-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(168,85,247,.12);
            border: 1px solid rgba(168,85,247,.25);
            color: #c084fc;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .4px;
        }

        .ai-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #a855f7;
            animation: onlinePulse 2s infinite;
        }

        .ai-section-title {
            color: var(--pro-text);
            font-size: 14px;
            font-weight: 700;
        }

        .ai-section-subtitle {
            color: #7f8da0;
            font-size: 10px;
            margin-top: 2px;
        }

        /* Kartu ringkasan atas (3 kartu: Konsumsi, Biaya, Waktu Prediksi) */
        .ai-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 14px;
        }

        @media (max-width: 700px) {
            .ai-summary-grid { grid-template-columns: 1fr; }
        }

        .ai-card {
            background: #0d1621;
            border: 1px solid var(--pro-border);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 7px 22px rgba(0,0,0,.20);
            transition: transform .15s, box-shadow .15s;
        }

        .ai-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 9px 26px rgba(0,0,0,.28);
        }

        .ai-card-label {
            color: #8291a4;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .45px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .ai-card-value {
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .ai-card-unit {
            font-size: 12px;
            font-weight: 500;
            margin-left: 4px;
            opacity: .7;
        }

        .ai-card-sub {
            color: #7f8da0;
            font-size: 10px;
            margin-top: 6px;
        }

        .ai-card--kwh   { border-top: 2px solid #22d3ee; }
        .ai-card--kwh .ai-card-value { color: #22d3ee; }

        .ai-card--rp    { border-top: 2px solid #22c55e; }
        .ai-card--rp .ai-card-value { color: #22c55e; }

        .ai-card--time  { border-top: 2px solid #a855f7; }
        .ai-card--time .ai-card-value { color: #a855f7; font-size: 15px; }

        /* Panel tabel token */
        .ai-token-panel {
            background: linear-gradient(145deg, rgba(15,23,35,.98), rgba(10,17,27,.98));
            border: 1px solid var(--pro-border);
            border-radius: 16px;
            padding: 21px;
            box-shadow: 0 7px 22px rgba(0,0,0,.20);
        }

        .ai-token-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .ai-token-table th {
            color: #7f8da0;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .4px;
            text-transform: uppercase;
            text-align: left;
            padding: 0 10px 10px 0;
            border-bottom: 1px solid var(--pro-border);
        }

        .ai-token-table td {
            padding: 11px 10px 11px 0;
            font-size: 12px;
            color: var(--pro-text);
            border-bottom: 1px solid rgba(28,42,58,.6);
            vertical-align: middle;
        }

        .ai-token-table tr:last-child td { border-bottom: none; }

        .ai-token-table tr:hover td { background: rgba(255,255,255,.018); }

        .ai-nominal-chip {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            background: rgba(47,129,247,.10);
            border: 1px solid rgba(47,129,247,.20);
            color: #60a5fa;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .ai-bar-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ai-bar-bg {
            flex: 1;
            height: 5px;
            border-radius: 99px;
            background: rgba(255,255,255,.06);
            overflow: hidden;
        }

        .ai-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #a855f7, #6366f1);
            transition: width .6s ease;
        }

        .ai-pct {
            font-size: 10px;
            color: #a855f7;
            font-weight: 700;
            min-width: 36px;
            text-align: right;
        }

        .ai-alarm-time {
            color: #f59e0b;
            font-size: 11px;
        }

        .ai-loading {
            color: #748398;
            font-size: 12px;
            text-align: center;
            padding: 24px 0;
        }

        .ai-error {
            color: #fca5a5;
            font-size: 12px;
            text-align: center;
            padding: 20px 0;
        }

        @media (max-width: 700px) {
            .ai-token-table th:nth-child(4),
            .ai-token-table td:nth-child(4) { display: none; }
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


        </section>


    <!-- ======================================================

         MAIN GRID

    ======================================================= -->


    <!-- ======================================================

         GRAFIK MONITORING

    ======================================================= -->

    <section class="charts-grid">

        <!-- HISTORY CONTROLS -->
        <div class="history-toolbar">
            <div class="history-info">
                <div class="panel-title">Riwayat Monitoring</div>
                <div class="panel-subtitle">Grafik 6 jam • Gunakan navigasi tanggal dan periode untuk melihat riwayat</div>
            </div>

            <div class="history-controls">
                <div class="date-navigation">
                    <button type="button" class="history-btn" id="prevDate" aria-label="Hari sebelumnya">‹</button>
                    <div class="date-history-label" id="historyDateLabel">—</div>
                    <button type="button" class="history-btn" id="nextDate" aria-label="Hari berikutnya">›</button>
                </div>

                <div class="period-navigation">
                    <button type="button" class="history-btn" id="prevWindow" aria-label="Periode sebelumnya">‹</button>
                    <div class="window-label" id="windowLabel">—</div>
                    <button type="button" class="history-btn" id="nextWindow" aria-label="Periode berikutnya">›</button>
                </div>
            </div>
        </div>

        <div class="panel chart-panel">

            <div class="panel-header"><div><div class="panel-title">Pemakaian Daya</div><div class="panel-subtitle">Rata-rata daya aktif setiap menit</div></div></div>

            <div class="chart-wrapper"><canvas id="dailyChart"></canvas></div>

        </div>

        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Tegangan</div><div class="panel-subtitle">Rata-rata tegangan setiap menit</div></div></div><div class="chart-wrapper"><canvas id="voltageChart"></canvas></div></div>

        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Arus</div><div class="panel-subtitle">Rata-rata arus setiap menit</div></div></div><div class="chart-wrapper"><canvas id="currentChart"></canvas></div></div>

        <div class="panel chart-panel"><div class="panel-header"><div><div class="panel-title">Frekuensi</div><div class="panel-subtitle">Rata-rata frekuensi setiap menit</div></div></div><div class="chart-wrapper"><canvas id="frequencyChart"></canvas></div></div>

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

        </div>

    </section>


    <!-- ======================================================

         AI PREDIKSI LISTRIK & ESTIMASI TOKEN PLN

    ======================================================= -->

    <section class="ai-section">

        <!-- HEADER SECTION -->
        <div class="ai-section-header">
            <div class="ai-badge">
                <span class="ai-badge-dot"></span>
                AI PREDIKSI
            </div>
            <div>
                <div class="ai-section-title">Prediksi Konsumsi & Estimasi Token PLN</div>
                <div class="ai-section-subtitle">Dihitung otomatis oleh model Machine Learning berdasarkan pemakaian harian</div>
            </div>
        </div>

        <!-- KARTU RINGKASAN ATAS -->
        <div class="ai-summary-grid">

            <div class="ai-card ai-card--kwh">
                <div class="ai-card-label">Prediksi Konsumsi Hari Ini</div>
                <div class="ai-card-value">
                    <span id="aiKwhHariIni">—</span>
                    <span class="ai-card-unit">kWh</span>
                </div>
                <div class="ai-card-sub" id="aiKwhStabil">Rata-rata stabil: — kWh/hari</div>
            </div>

            <div class="ai-card ai-card--rp">
                <div class="ai-card-label">Estimasi Kebutuhan 1 Bulan</div>
                <div class="ai-card-value">
                    <span id="aiKwhBulanan">—</span>
                    <span class="ai-card-unit">kWh</span>
                </div>
                <div class="ai-card-sub" id="aiBiayaBulanan">Estimasi biaya: Rp —</div>
            </div>

            <div class="ai-card ai-card--time">
                <div class="ai-card-label">Waktu Prediksi</div>
                <div class="ai-card-value" id="aiWaktuPrediksi">—</div>
                <div class="ai-card-sub" id="aiSource">Sumber data: —</div>
            </div>

        </div>
         <!-- PANEL GRAFIK PREDIKSI VS AKTUAL -->
            <div class="panel chart-panel" style="--chart-accent: #a855f7; margin-bottom: 18px; margin-top: 16px;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Evaluasi Prediksi AI vs Konsumsi Realisasi</div>
                        <div class="panel-subtitle">Perbandingan hasil prediksi model AI dengan konsumsi aktual harian (kWh)</div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="predictionChart"></canvas>
                </div>
            </div>
            

        <!-- PANEL TABEL SIMULASI TOKEN -->
        <div class="ai-token-panel">

            <div class="panel-header" style="margin-bottom:4px">
                <div>
                    <div class="panel-title">Simulasi Kecukupan Token Listrik PLN</div>
                    <div class="panel-subtitle">Estimasi daya tahan dan persentase kebutuhan bulanan per nominal token</div>
                </div>
            </div>

            <div id="aiTokenContent">
                <div class="ai-loading">⟳ Memuat prediksi AI...</div>
            </div>

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

       SECURITY — Nonaktifkan DevTools shortcuts & console

    ======================================================== */

    // (function () {

    //     /* Matikan semua output console agar tidak membocorkan info */
    //     var _noop = function () {};
    //     console.log   = _noop;
    //     console.warn  = _noop;
    //     console.info  = _noop;
    //     console.debug = _noop;
    //     console.error = _noop;
    //     console.table = _noop;
    //     console.trace = _noop;

    //     /* Nonaktifkan klik kanan */
    //     document.addEventListener('contextmenu', function (e) {
    //         e.preventDefault();
    //     });

    //     /* Blokir shortcut keyboard yang umum dipakai membuka DevTools */
    //     document.addEventListener('keydown', function (e) {
    //         var blocked =
    //             e.key === 'F12' ||
    //             (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
    //             (e.ctrlKey && e.key === 'u') ||
    //             (e.ctrlKey && e.key === 'U') ||
    //             (e.ctrlKey && e.key === 's') ||
    //             (e.ctrlKey && e.key === 'S');
    //         if (blocked) { e.preventDefault(); }
    //     });

    // })();


    /* ========================================================

       GLOBAL

    ======================================================== */

    let dailyChart;
    let voltageChart;
    let currentChart;
    let frequencyChart;
    let predictionChart;

    let historyData = [];
    let currentWindow = 0;
    let followingLive = true;

    const HISTORY_WINDOWS = [
        { start: 0, end: 360, label: '00:00 – 06:00' },
        { start: 360, end: 720, label: '06:00 – 12:00' },
        { start: 720, end: 1080, label: '12:00 – 18:00' },
        { start: 1080, end: 1440, label: '18:00 – 00:00' }
    ];

    function getJakartaDateKey(date = new Date()) {
        const parts = new Intl.DateTimeFormat('en-US', {
            timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit'
        }).formatToParts(date);
        const getPart = type => parts.find(part => part.type === type)?.value;
        return `${getPart('year')}-${getPart('month')}-${getPart('day')}`;
    }

    function shiftDateKey(dateKey, days) {
        const [year, month, day] = dateKey.split('-').map(Number);
        const date = new Date(Date.UTC(year, month - 1, day));
        date.setUTCDate(date.getUTCDate() + days);
        return date.toISOString().slice(0, 10);
    }

    function formatHistoryDate(dateKey) {
        const [year, month, day] = dateKey.split('-').map(Number);
        const date = new Date(Date.UTC(year, month - 1, day, 12, 0, 0));
        return new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta', weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
        }).format(date);
    }

    let selectedHistoryDate = getJakartaDateKey();

    function getActiveWindowIndex() {
        if (selectedHistoryDate !== getJakartaDateKey()) return 3;

        const now = new Date();
        const hour = Number(new Intl.DateTimeFormat('en-US', {
            timeZone: 'Asia/Jakarta', hour: '2-digit', hour12: false
        }).format(now));

        if (hour < 6) return 0;
        if (hour < 12) return 1;
        if (hour < 18) return 2;
        return 3;
    }

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


            document.getElementById('frequency')

                .textContent =

                data.frequency ?? '—';


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

                                accent + '28',


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

                                    'rgba(116,131,152,.16)'

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

    function createPredictionChart(labels, dataPrediksi, dataReal) {
        const ctx = document.getElementById('predictionChart').getContext('2d'); // Sesuaikan ID canvas kamu

        // Hapus grafik lama agar tidak menumpuk saat refresh
        if (window.myPredictionChart) {
            window.myPredictionChart.destroy();
        }

        window.myPredictionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Prediksi Konsumsi (kWh)',
                        data: dataPrediksi,
                        borderColor: '#a855f7', // Warna ungu (AI)
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Konsumsi Real (kWh)',
                        data: dataReal,
                        borderColor: '#10b981', // Warna hijau (Real sensor)
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { color: '#94a3b8' }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        beginAtZero: true,
                        min: 0, // Memaksa batas bawah sumbu Y tetap 0
                        suggestedMax: 15 // Batas atas default jika data sedang kosong
                    }
                }
            }
        });
    }

    // Panggil fungsi saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', loadPrediction);

    /* ========================================================

       LOAD HISTORY

       1 MENIT / TITIK

       24 JAM = 1440 TITIK

    ======================================================== */


    async function loadDailyHistory() {

        if (followingLive) {
            selectedHistoryDate = getJakartaDateKey();
            currentWindow = getActiveWindowIndex();
        }

        try {
            const response = await fetch(
                `/api/readings/history?date=${selectedHistoryDate}`,
                { cache: 'no-store' }
            );

            const result = await response.json();

            if (!result.success) {
                console.error('History gagal:', result);
                return;
            }

            historyData = result.data || [];
            renderHistoryWindow();

            console.log(`History berhasil dimuat: ${historyData.length} titik untuk ${selectedHistoryDate}`);

        } catch (error) {
            console.error('Detail Error:', err); // Tambahkan ini untuk melihat penyebab pastinya di F12 Console
            document.getElementById('aiTokenContent').innerHTML =
                '<div class="ai-error">⚠️ Koneksi ke API prediksi gagal.</div>';
        }

    }


    function renderHistoryWindow() {

        const windowInfo =
            HISTORY_WINDOWS[currentWindow];

        const visibleData =
            historyData.slice(
                windowInfo.start,
                windowInfo.end
            );

        window.historyLabels =
            visibleData.map(
                item => item.time
            );

        dailyChart =
            createLineChart(
                dailyChart,
                'dailyChart',
                'Rata-rata Daya (W)',
                visibleData.map(
                    item => item.average_power
                ),
                'Daya (W)',
                '#3b82f6',
                'W'
            );

        voltageChart =
            createLineChart(
                voltageChart,
                'voltageChart',
                'Rata-rata Tegangan (V)',
                visibleData.map(
                    item => item.average_voltage
                ),
                'Tegangan (V)',
                '#22d3ee',
                'V'
            );

        currentChart =
            createLineChart(
                currentChart,
                'currentChart',
                'Rata-rata Arus (A)',
                visibleData.map(
                    item => item.average_current
                ),
                'Arus (A)',
                '#f59e0b',
                'A'
            );

        frequencyChart =
            createLineChart(
                frequencyChart,
                'frequencyChart',
                'Rata-rata Frekuensi (Hz)',
                visibleData.map(
                    item => item.average_frequency
                ),
                'Frekuensi (Hz)',
                '#a855f7',
                'Hz'
            );

        updateHistoryNavigation();

    }


    function updateDateNavigation() {

        const label = document.getElementById('historyDateLabel');
        const prev = document.getElementById('prevDate');
        const next = document.getElementById('nextDate');
        const today = getJakartaDateKey();

        if (label) label.textContent = formatHistoryDate(selectedHistoryDate);
        if (prev) prev.disabled = false;
        if (next) next.disabled = selectedHistoryDate >= today;
    }


    function updateHistoryNavigation() {

        const label = document.getElementById('windowLabel');
        const prev = document.getElementById('prevWindow');
        const next = document.getElementById('nextWindow');
        const activeWindow = getActiveWindowIndex();
        const isLive = selectedHistoryDate === getJakartaDateKey() && currentWindow === activeWindow;

        if (label) {
            label.textContent = HISTORY_WINDOWS[currentWindow].label + (isLive ? ' • LIVE' : ' • RIWAYAT');
            label.classList.toggle('live', isLive);
        }

        if (prev) prev.disabled = currentWindow === 0;
        if (next) next.disabled = currentWindow >= activeWindow;

        updateDateNavigation();
    }


    function changeHistoryDate(direction) {
    const today = getJakartaDateKey();
    const target = shiftDateKey(selectedHistoryDate, direction);

    if (target > today) return;

    selectedHistoryDate = target;

    if (selectedHistoryDate === today) {
        followingLive = true;
        currentWindow = getActiveWindowIndex();
    } else {
        followingLive = false;
        currentWindow = 3;
    }

    // Terapkan tanggal & jam yang sama ke modul Prediksi AI
    predictDateObj = new Date(selectedHistoryDate);
    currentPredictPeriodIdx = currentWindow;

    // Load Riwayat + Prediksi AI bersamaan
    loadDailyHistory();
    loadPrediction();
}

function changeHistoryWindow(direction) {
    const activeWindow = getActiveWindowIndex();
    const today = getJakartaDateKey();
    const target = currentWindow + direction;

    // Jika digeser ke kiri melebihi jam 00:00 - 06:00 -> Mundur ke H-1 (18:00 - 00:00)
    if (target < 0) {
        changeHistoryDate(-1);
        return;
    }

    // Jika digeser ke kanan melebihi jam 18:00 - 00:00 pada tanggal lampau -> Maju ke H+1 (00:00 - 06:00)
    const maxWindowLimit = (selectedHistoryDate === today) ? activeWindow : 3;
    if (target > maxWindowLimit) {
        if (selectedHistoryDate < today) {
            selectedHistoryDate = shiftDateKey(selectedHistoryDate, 1);
            currentWindow = 0;
            followingLive = (selectedHistoryDate === today && currentWindow === activeWindow);
            
            predictDateObj = new Date(selectedHistoryDate);
            currentPredictPeriodIdx = currentWindow;
            
            loadDailyHistory();
            loadPrediction();
        }
        return;
    }

    currentWindow = target;
    followingLive = (selectedHistoryDate === today && currentWindow === activeWindow);

    // Selaraskan indeks periode jam untuk AI
    currentPredictPeriodIdx = currentWindow;

    if (historyData.length) renderHistoryWindow();
    else updateHistoryNavigation();

    // Update grafik Prediksi AI sesuai periode jam baru
    loadPrediction();
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


    document.getElementById(
        'prevDate'
    ).addEventListener(
        'click',
        function() {
            changeHistoryDate(-1);
        }
    );

    document.getElementById(
        'nextDate'
    ).addEventListener(
        'click',
        function() {
            changeHistoryDate(1);
        }
    );

    document.getElementById(
        'prevWindow'
    ).addEventListener(
        'click',
        function() {
            changeHistoryWindow(-1);
        }
    );

    document.getElementById(
        'nextWindow'
    ).addEventListener(
        'click',
        function() {
            changeHistoryWindow(1);
        }
    );

    selectedHistoryDate = getJakartaDateKey();
    currentWindow = getActiveWindowIndex();
    updateDateNavigation();
    updateHistoryNavigation();


    /* ========================================================

       AI PREDICTION LOADER

    ======================================================== */

   // State tanggal & periode navigasi prediksi
    const TODAY_OBJ = new Date(); // Hari ini: 25 Sep 2026
    let predictDateObj = new Date(); // Default filter ke hari ini

    const predictPeriods = [
        "00:00 – 06:00",
        "06:00 – 12:00 • LIVE",
        "12:00 – 18:00",
        "18:00 – 24:00"
    ];
    let currentPredictPeriodIdx = 1; // Default ke "06:00 - 12:00 • LIVE"

    // Format tanggal ke header tampilan: "Jum, 25 Sep 2026"
    function formatPredictDateHeader(date) {
        return date.toLocaleDateString('id-ID', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    // Generator 7 hari label tanggal mundur hingga tanggal yang dipilih
    function get7DaysLabels(endDate) {
        const labels = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date(endDate);
            d.setDate(d.getDate() - i);
            const day = d.getDate();
            const month = d.toLocaleDateString('id-ID', { month: 'short' });
            labels.push(`${day} ${month}`);
        }
        return labels;
    }

    function updatePeriodDisplay() {
    const periodDisp = document.getElementById('periodDisplay');
    if (!periodDisp) return;

    // Mengambil label jam dari HISTORY_WINDOWS sesuai window/periode yang aktif
    const windowInfo = HISTORY_WINDOWS[currentWindow];
    const today = getJakartaDateKey();
    const activeWindow = getActiveWindowIndex();
    const isLive = selectedHistoryDate === today && currentWindow === activeWindow;

    const labelText = windowInfo ? windowInfo.label : '';
    periodDisp.textContent = labelText + (isLive ? ' • LIVE' : ' • RIWAYAT');

    if (isLive) {
        periodDisp.classList.add('badge-live');
    } else {
        periodDisp.classList.remove('badge-live');
    }
}

    async function loadPrediction() {

        // Sinkronkan dengan filter utama
    if (selectedHistoryDate) {
        predictDateObj = new Date(selectedHistoryDate);
    }
    currentPredictPeriodIdx = currentWindow;

    const dateDisp = document.getElementById('dateDisplay');
    if (dateDisp) dateDisp.textContent = formatPredictDateHeader(predictDateObj);
    updatePeriodDisplay();
        const btnNextD = document.getElementById('btnNextDate');

        // 1. Update Teks Navigasi Tanggal
        if (dateDisp) dateDisp.textContent = formatPredictDateHeader(predictDateObj);

        // 2. Kunci Tombol Panah Kanan jika sudah Hari Ini
        if (btnNextD) {
            const isTodayOrFuture = predictDateObj.toDateString() === TODAY_OBJ.toDateString() || predictDateObj > TODAY_OBJ;
            btnNextD.disabled = isTodayOrFuture;
            btnNextD.style.opacity = isTodayOrFuture ? "0.35" : "1";
            btnNextD.style.cursor = isTodayOrFuture ? "not-allowed" : "pointer";
        }

        // 3. Generate Label Tanggal 7 Hari
        const labels = get7DaysLabels(predictDateObj);

        // Nilai Default / Dummy jika API belum siap
        let kwhHariIni = "10.0";
        let kwhStabil = "4.2";
        let kwhBulanan = "126";
        let biayaBulanan = "182.000";
        let waktuPrediksi = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + " WIB";
        let sourceData = "📡 Simulasi Data Baseline / ESP32";

        let dataPrediksi = [0, 0, 0, 0, 0, 0, 10];
        let dataReal = [0, 0, 0, 0, 0, 0, 0.2];

        // Tambahkan deklarasi ini di dalam fungsi loadPrediction() sebelum try-catch / perulangan
        let tokensData = {
            "20k":   { nominal_rp: 20000,   kwh_didapat: "14.79",  daya_tahan: "2 Hari 5 Jam",   persen_kebutuhan_sebulan: 7.4,   perkiraan_alarm_bunyi: "Saturday, 26 Sep 2026 pukul 18:19 WIB" },
            "50k":   { nominal_rp: 50000,   kwh_didapat: "36.98",  daya_tahan: "5 Hari 13 Jam",  persen_kebutuhan_sebulan: 18.4,  perkiraan_alarm_bunyi: "Wednesday, 30 Sep 2026 pukul 01:56 WIB" },
            "100k":  { nominal_rp: 100000,  kwh_didapat: "73.96",  daya_tahan: "11 Hari 1 Jam",  persen_kebutuhan_sebulan: 36.9,  perkiraan_alarm_bunyi: "Monday, 05 Oct 2026 pukul 14:35 WIB" },
            "200k":  { nominal_rp: 200000,  kwh_didapat: "147.93", daya_tahan: "22 Hari 3 Jam",  persen_kebutuhan_sebulan: 73.7,  perkiraan_alarm_bunyi: "Friday, 16 Oct 2026 pukul 15:57 WIB" },
            "500k":  { nominal_rp: 500000,  kwh_didapat: "369.82", daya_tahan: "55 Hari 7 Jam",  persen_kebutuhan_sebulan: 184.8, perkiraan_alarm_bunyi: "Wednesday, 18 Nov 2026 pukul 19:58 WIB" },
            "1000k": { nominal_rp: 1000000, kwh_didapat: "739.64", daya_tahan: "110 Hari 1 Jam", persen_kebutuhan_sebulan: 369.8, perkiraan_alarm_bunyi: "Wednesday, 13 Jan 2027 pukul 02:41 WIB" }
        };

        try {
            const formattedApiDate = predictDateObj.toISOString().split('T')[0];
            const url = `/api/predict?date=${formattedApiDate}&days=7`;
            const res = await fetch(url, { cache: 'no-store' });

            if (res.ok) {
                const json = await res.json();
                if (json.success && json.data) {
                    const d = json.data;
                    
                    // Timpa data jika dikirim oleh API
                    if (d.prediksi_hari_ini_kwh !== undefined) kwhHariIni = d.prediksi_hari_ini_kwh;
                    if (d.rata_rata_harian_stabil_kwh !== undefined) kwhStabil = d.rata_rata_harian_stabil_kwh;
                    if (d.estimasi_kebutuhan_sebulan_kwh !== undefined) kwhBulanan = d.estimasi_kebutuhan_sebulan_kwh;
                    if (d.estimasi_biaya_sebulan_rp !== undefined) {
                        biayaBulanan = Number(d.estimasi_biaya_sebulan_rp).toLocaleString('id-ID');
                    }
                    if (d.waktu_prediksi) waktuPrediksi = d.waktu_prediksi;
                    if (json.source) {
                        sourceData = json.source === 'live_sensor' ? '📡 Data sensor langsung (ESP32)' : '📊 Data baseline 900 VA';
                    }

                    if (d.riwayat_evaluasi && Array.isArray(d.riwayat_evaluasi) && d.riwayat_evaluasi.length > 0) {
                        dataPrediksi = d.riwayat_evaluasi.map(item => Number(item.kwh_prediksi) || 0);
                        dataReal = d.riwayat_evaluasi.map(item => Number(item.kwh_real) || 0);
                    }
                }
            }
        } catch (err) {
            console.error("API belum mengembalikan data, menggunakan nilai fallback:", err);
        }

        // 4. PASTI UPDATE KARTU RINGKASAN ATAS
        const elHariIni = document.getElementById('aiKwhHariIni');
        const elStabil = document.getElementById('aiKwhStabil');
        const elBulanan = document.getElementById('aiKwhBulanan');
        const elBiaya = document.getElementById('aiBiayaBulanan');
        const elWaktu = document.getElementById('aiWaktuPrediksi');
        const elSource = document.getElementById('aiSource');

        if (elHariIni) elHariIni.textContent = kwhHariIni;
        if (elStabil) elStabil.textContent = `Rata-rata stabil: ${kwhStabil} kWh/hari`;
        if (elBulanan) elBulanan.textContent = kwhBulanan;
        if (elBiaya) elBiaya.textContent = `Estimasi biaya: Rp ${biayaBulanan}`;
        if (elWaktu) elWaktu.textContent = waktuPrediksi;
        if (elSource) elSource.textContent = sourceData;

        // 5. Render Grafik
        createPredictionChart(labels, dataPrediksi, dataReal);

        // 6. RENDER TABEL SIMULASI TOKEN (Gunakan Data API atau Fallback Dummy)
        const tokenContainer = document.getElementById('aiTokenContent');
        if (tokenContainer) {
            let rows = '';
            for (const key in tokensData) {
                const t = tokensData[key];
                const pctVal = t.persen_kebutuhan_sebulan || 0;
                const pct = Math.min(pctVal, 100);
                const pctLabel = pctVal > 100 ? `>${pct}%` : `${pct}%`;

                rows += `
                    <tr>
                        <td><span class="ai-nominal-chip">Rp ${Number(t.nominal_rp || 0).toLocaleString('id-ID')}</span></td>
                        <td style="color:#22d3ee;font-weight:700">${t.kwh_didapat || 0} kWh</td>
                        <td style="font-weight:600">${t.daya_tahan || '-'}</td>
                        <td>
                            <div class="ai-bar-wrap">
                                <div class="ai-bar-bg"><div class="ai-bar-fill" style="width:${pct}%"></div></div>
                                <span class="ai-pct">${pctLabel}</span>
                            </div>
                        </td>
                        <td><span class="ai-alarm-time">${t.perkiraan_alarm_bunyi || '-'}</span></td>
                    </tr>`;
            }

            tokenContainer.innerHTML = `
                <table class="ai-token-table">
                    <thead>
                        <tr>
                            <th>Nominal</th>
                            <th>kWh Didapat</th>
                            <th>Daya Tahan</th>
                            <th>Kebutuhan Bulanan</th>
                            <th>Perkiraan Alarm</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>`;
        }
}

    loadPrediction();
    /* Perbarui prediksi setiap 5 menit */
    setInterval(loadPrediction, 5 * 60 * 1000);

</script>


</body>

</html>