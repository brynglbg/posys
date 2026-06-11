<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>404 | Page not found</title>
  <meta name="description" content="Ops! La pagina che cerchi non esiste. Torna alla home o prova una ricerca." />
  <meta name="theme-color" content="#0b1220" />
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='256' height='256'%3E%3Crect width='100%25' height='100%25' fill='%230b1220'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='monospace' font-size='120' fill='%23ffffff'%3E404%3C/text%3E%3C/svg%3E">
  <style>
    :root{
      --bg-1:#0b1220; --bg-2:#111827; --fg:#e6edf3; --muted:#9aa4b2; --brand:#7c3aed; --accent:#22d3ee; --glass:rgba(255,255,255,.08); --card:#0f1629; --glow:#7c3aed55;
    }
    /* Palette alternative (ciclabili da JS) */
    body.palette-aurora{--bg-1:#0b1220;--bg-2:#111827;--brand:#7c3aed;--accent:#22d3ee;--card:#0f1629;--glow:#7c3aed55;}
    body.palette-sunset{--bg-1:#150b1a;--bg-2:#1d0f2a;--brand:#ff6b6b;--accent:#ffd166;--card:#1a1022;--glow:#ff6b6b55;}
    body.palette-forest{--bg-1:#0b1512;--bg-2:#0f2018;--brand:#34d399;--accent:#60a5fa;--card:#0d1b16;--glow:#34d39955;}
    body.palette-ocean{--bg-1:#06141f;--bg-2:#0a2233;--brand:#3b82f6;--accent:#22d3ee;--card:#0a1c29;--glow:#3b82f655;}
    /* Resetter */
    *,*::before,*::after{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
      color:var(--fg); background: radial-gradient(1200px 1200px at 80% -10%, var(--bg-2), transparent 60%), linear-gradient(180deg,var(--bg-1),var(--bg-2));
      overflow:hidden; /* per il canvas full-screen */
    }
    /* Sfondo animato morbido */
    .bg-gradient{
      position:fixed; inset:0; pointer-events:none; z-index:-2;
      background:
        radial-gradient(1200px 800px at 10% 10%, color-mix(in oklab,var(--brand) 40%, transparent), transparent 60%),
        radial-gradient(1200px 800px at 90% 90%, color-mix(in oklab,var(--accent) 40%, transparent), transparent 60%);
      filter:saturate(120%);
      animation: drift 18s ease-in-out infinite alternate;
    }
    @keyframes drift{from{transform:translate3d(0,0,0)} to{transform:translate3d(2vw,-2vh,0) scale(1.02)}}
    /* Grana delicata */
    .noise{
      position:fixed; inset:-50%; z-index:-1; pointer-events:none; opacity:.06; mix-blend-mode:soft-light;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='.9' numOctaves='2' seed='3'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.8'/%3E%3C/svg%3E");
      animation:noiseMove 12s linear infinite;
    }
    @keyframes noiseMove{to{transform:translate3d(5%,5%,0)}}
    /* Canvas particelle */
    canvas.starfield{position:fixed; inset:0; z-index:-3}
    /* Contenuto centrale */
    .wrap{
      min-height:100dvh; display:grid; place-items:center; padding:24px;
    }
    .card{
      width:min(900px,92vw); border-radius:20px; background:linear-gradient(180deg, color-mix(in oklab, var(--card) 92%, transparent), color-mix(in oklab, var(--card) 88%, transparent));
      border:1px solid color-mix(in oklab, var(--fg) 8%, transparent);
      box-shadow:0 30px 120px var(--glow), inset 0 1px 0 rgba(255,255,255,.05);
      backdrop-filter: blur(10px) saturate(110%); -webkit-backdrop-filter: blur(10px) saturate(110%);
      position:relative; overflow:hidden;
    }
    .card::after{
      content:""; position:absolute; inset:-1px; border-radius:inherit; pointer-events:none;
      background: conic-gradient(from 180deg at 50% 50%, transparent 0 30deg, color-mix(in oklab,var(--brand) 45%, transparent) 60deg, transparent 120deg, color-mix(in oklab,var(--accent) 45%, transparent) 170deg, transparent 210deg);
      filter:blur(18px) opacity(.22);
    }
    .inner{padding:clamp(24px,4vw,44px); position:relative; z-index:1}
    /* 404 Glitch */
    .code{
      font-size:clamp(64px,12vw,160px); font-weight:800; letter-spacing:-.04em; line-height:.8; margin:0;
      position:relative; display:inline-block; text-shadow:0 6px 30px color-mix(in oklab,var(--brand) 40%, transparent);
      isolation:isolate;
    }
    .code::before,.code::after{
      content:attr(data-text); position:absolute; inset:0; pointer-events:none; mix-blend-mode:screen;
    }
    .code::before{color:var(--brand); transform:translate(-10px,0); clip-path:inset(10% 0 30% 0); animation:gl1 2.2s infinite steps(2,end)}
    .code::after{color:var(--accent); transform:translate(10px,0); clip-path:inset(60% 0 5% 0); animation:gl2 1.8s infinite steps(2,end)}
    @keyframes gl1{50%{transform:translate(-2px,0)}}
    @keyframes gl2{50%{transform:translate(2px,0)}}
    .title{
      font-family: monospace;
      white-space: nowrap;
      overflow: hidden;
      border-right: 3px solid black;
      width: 0;
      animation: typing 3s steps(14) infinite, blink 0.6s step-end infinite;
    }
    @keyframes typing{
      0% { width: 0 }
      40% { width: 15ch }
      60% { width: 15ch }
      100% { width: 0 }
    }
    @keyframes blink{
      50% { border-color: transparent }
    }
    .muted{color:var(--muted); font-size:clamp(14px,2.2vw,18px); margin:.6rem 0 1.2rem; max-width:70ch}
    .actions{display:flex; flex-wrap:wrap; gap:10px; margin:18px 0 6px}
    .btn{
      --p: var(--brand);
      display:inline-flex; align-items:center; gap:8px; border:1px solid color-mix(in oklab, var(--p) 40%, transparent);
      padding:12px 16px; border-radius:12px; font-weight:600; color:var(--fg); text-decoration:none;
      background: linear-gradient(180deg, color-mix(in oklab, var(--p) 24%, transparent), transparent);
      box-shadow: 0 6px 20px color-mix(in oklab, var(--p) 22%, transparent), inset 0 1px 0 rgba(255,255,255,.05);
      transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
    }
    .btn:hover{transform: translateY(-1px); box-shadow:0 10px 28px color-mix(in oklab, var(--p) 30%, transparent)}
    .btn.secondary{--p: var(--accent)}
    .btn.ghost{--p: #ffffff22; border-color:#ffffff22; background:transparent}
    .btn .ic{font-variation-settings:"FILL" 1; font-family: "Material Symbols Outlined", system-ui, sans-serif;}
    /* Ricerca */
    .search{display:flex; gap:8px; align-items:center; margin-top:14px}
    .search input{
      flex:1; padding:14px 16px; border-radius:12px; border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.06); color:var(--fg);
      outline:none; transition:border-color .2s ease, background .2s ease;
    }
    .search input::placeholder{color:color-mix(in oklab,var(--muted) 80%, transparent)}
    .search input:focus{border-color:color-mix(in oklab,var(--accent) 50%, transparent); background:rgba(255,255,255,.09)}
    .chips{display:flex; flex-wrap:wrap; gap:8px; margin-top:10px}
    .chip{padding:8px 12px; border-radius:999px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); font-size:13px; cursor:pointer}
    .chip:hover{background:rgba(255,255,255,.1)}
    /* Barra utility */
    .util{
      position:absolute; inset:auto 16px 16px auto; display:flex; gap:8px; align-items:center; z-index:2;
    }
    .toggle, .palette{
      width:44px; height:44px; border-radius:12px; display:grid; place-items:center; cursor:pointer; user-select:none;
      border:1px solid rgba(255,255,255,.16); background:rgba(255,255,255,.06); box-shadow:inset 0 1px 0 rgba(255,255,255,.05);
      transition:transform .15s ease;
    }
    .toggle:hover,.palette:hover{transform:translateY(-1px)}
    .sr-only{position:absolute !important; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0}
    /* Footer mini */
    .foot{display:flex; justify-content:space-between; align-items:center; gap:12px; margin-top:22px; color:var(--muted); font-size:13px}
    .links{display:flex; flex-wrap:wrap; gap:10px}
    .links a{color:var(--muted); text-decoration:none; border-bottom:1px dashed transparent}
    .links a:hover{color:var(--fg); border-bottom-color:var(--muted)}
    /* Blob decorativo */
    .blob{
      position:absolute; width:500px; height:500px; border-radius:40% 60% 50% 50% / 55% 45% 55% 45%;
      background: radial-gradient(circle at 30% 30%, color-mix(in oklab,var(--brand) 40%, transparent), transparent 60%),
                  radial-gradient(circle at 70% 70%, color-mix(in oklab,var(--accent) 40%, transparent), transparent 60%);
      filter: blur(50px) saturate(140%); opacity:.35; z-index:0; pointer-events:none;
      animation: morph 16s ease-in-out infinite alternate;
    }
    .blob.one{top:-140px; left:-120px}
    .blob.two{bottom:-160px; right:-120px; animation-duration:20s}
    @keyframes morph{
      0%{transform:translate3d(0,0,0) rotate(0deg) scale(1)}
      100%{transform:translate3d(20px,-14px,0) rotate(10deg) scale(1.06)}
    }
    @media (prefers-reduced-motion: reduce){
      .bg-gradient,.noise,.blob,.code::before,.code::after{animation:none !important}
      canvas.starfield{display:none}
    }
    @media (max-width:520px){ .foot{flex-direction:column; align-items:flex-start}}
    @font-face{
      font-family:"Material Symbols Outlined";
      src: url(data:font/woff2;base64,d09GMgABAAAAAAOkAAsAAAAABpQAAAP/AAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAA) format("woff2");
      font-weight:400; font-style:normal; font-display:swap
    }
  </style>
</head>
<body class="palette-sunset" data-theme="dark" aria-live="polite">
  <canvas class="starfield" aria-hidden="true"></canvas>
  <div class="bg-gradient" aria-hidden="true"></div>
  <div class="noise" aria-hidden="true"></div>
  <div class="wrap">
    <div class="card" role="group" aria-label="Pagina 404">
      <div class="blob one" aria-hidden="true"></div>
      <div class="blob two" aria-hidden="true"></div>
      <div class="inner">
        <h1 class="code" data-text="404" aria-label="Errore 404">404</h1>
        <p class="title">PAGE NOT FOUND</p>
      </div>
    </div>
  </div>
</body>
</html>