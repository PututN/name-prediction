<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #f6f5f2; --surface: #fff; --text: #1d1d1b; --muted: #6b6a66;
            --border: #e2e0da; --accent: #2f5d50; --accent-soft: #e3ece8; --danger: #b3261e;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; }
        main { max-width: 760px; margin: 0 auto; padding: 40px 16px 64px; }
        h1 { font-size: 1.6rem; margin: 0 0 4px; }
        .sub { color: var(--muted); margin: 0 0 24px; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        form.search { display: flex; gap: 8px; }
        input[type=text] { flex: 1; min-width: 0; padding: 10px 12px; font-size: 1rem; border: 1px solid var(--border); border-radius: 8px; }
        input[type=text]:focus { outline: 2px solid var(--accent); outline-offset: 1px; }
        button { padding: 10px 18px; font-size: 1rem; border: 0; border-radius: 8px; background: var(--accent); color: #fff; cursor: pointer; }
        button:hover { filter: brightness(1.1); }
        .error { color: var(--danger); margin: 8px 0 0; font-size: .9rem; }
        @media (max-width: 600px) { form.search { flex-direction: column; } }
        h2 { font-size: 1.15rem; margin: 0 0 12px; }
        .meta { color: var(--muted); font-size: .85rem; }
        .row { display: grid; grid-template-columns: 1fr auto; gap: 4px 8px; margin-bottom: 10px; }
        .bar { grid-column: 1 / -1; height: 6px; background: var(--accent-soft); border-radius: 3px; overflow: hidden; }
        .bar span { display: block; height: 100%; background: var(--accent); }
        .num { font-variant-numeric: tabular-nums; }
        table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        th, td { text-align: left; padding: 8px 6px; border-bottom: 1px solid var(--border); vertical-align: top; }
        th { color: var(--muted); font-weight: 500; }
        td a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .link-btn { background: none; color: var(--danger); padding: 0; font-size: .85rem; }
        .empty { color: var(--muted); }
    </style>
</head>
<body>
<main>
    @yield('content')
</main>
</body>
</html>
