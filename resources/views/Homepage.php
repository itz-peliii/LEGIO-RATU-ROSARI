<?php
/**
 * homepage.php — Beranda website gereja (tema pantai)
 * Ganti data di bagian CONFIG di bawah sesuai gereja kamu.
 */
date_default_timezone_set('Asia/Jakarta');

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ===================== CONFIG ===================== */
$church = [
    'name'    => 'Gereja Samudra Kasih',
    'tagline' => 'Tempat pulang bagi setiap jiwa',
    'intro'   => 'Datang apa adanya. Bersama kami, kamu bisa bertumbuh dalam iman, komunitas, dan pelayanan.',
    'email'   => 'halo@samudrakasih.id',
    'phone'   => '+62 31 555 0123',
    'live'    => '#',   // link YouTube / live streaming
    'giving'  => '#',   // link halaman persembahan
];

$nav = [
    'Ibadah'    => '#ibadah',
    'Renungan'  => '#renungan',
    'Komunitas' => '#komunitas',
    'Lokasi'    => '#lokasi',
    'Giving'    => '#giving',
];

// day: 0 = Minggu, 1 = Senin, ... 6 = Sabtu
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$ibadah = [
    ['name' => 'Ibadah Raya 1',      'day' => 0, 'time' => '08:00', 'place' => 'Gedung Utama',  'note' => 'Ibadah keluarga, ada Sekolah Minggu'],
    ['name' => 'Ibadah Raya 2',      'day' => 0, 'time' => '10:30', 'place' => 'Gedung Utama',  'note' => 'Pujian dan firman, ada penerjemah isyarat'],
    ['name' => 'Ibadah Raya 3',      'day' => 0, 'time' => '17:00', 'place' => 'Gedung Utama',  'note' => 'Suasana lebih santai, cocok untuk anak muda'],
    ['name' => 'Doa Bersama',        'day' => 3, 'time' => '19:00', 'place' => 'Ruang Doa',     'note' => 'Satu jam berdoa untuk kota dan keluarga'],
    ['name' => 'Ibadah Pemuda',      'day' => 5, 'time' => '19:00', 'place' => 'Aula Lantai 2', 'note' => 'Untuk usia SMA sampai mahasiswa'],
];

$renungan = [
    ['title' => 'Tenang di Tengah Ombak',      'verse' => 'Markus 4:39',      'date' => '2026-10-07', 'excerpt' => 'Badai tidak selalu berhenti saat kita meminta, tetapi damai bisa hadir lebih dulu di dalam perahu.'],
    ['title' => 'Jala yang Dilemparkan Lagi',  'verse' => 'Lukas 5:5',        'date' => '2026-10-06', 'excerpt' => 'Setelah semalaman tanpa hasil, Petrus tetap menurunkan jala. Ketaatan kecil sering membuka hal besar.'],
    ['title' => 'Pasir dan Batu Karang',       'verse' => 'Matius 7:24-25',   'date' => '2026-10-05', 'excerpt' => 'Fondasi tidak terlihat dari luar, tetapi menentukan apa yang bertahan saat air naik.'],
];

$komunitas = [
    ['name' => 'Anak & Sekolah Minggu', 'desc' => 'Belajar firman lewat cerita dan permainan.',       'icon' => 'shell'],
    ['name' => 'Pemuda',                'desc' => 'Tempat bertumbuh bersama di usia SMA dan kuliah.',  'icon' => 'wave'],
    ['name' => 'Keluarga',              'desc' => 'Pasangan dan orang tua saling menguatkan.',        'icon' => 'sun'],
    ['name' => 'Kelompok Sel',          'desc' => 'Berkumpul kecil di rumah, setiap minggu.',          'icon' => 'anchor'],
    ['name' => 'Musik & Multimedia',    'desc' => 'Melayani lewat musik, kamera, dan desain.',         'icon' => 'note'],
    ['name' => 'Peduli Sesama',         'desc' => 'Aksi sosial dan bantuan bagi masyarakat sekitar.',  'icon' => 'heart'],
];

$lokasi = [
    ['name' => 'Gereja Pusat',  'address' => 'Jl. Pantai Indah No. 12, Surabaya',  'hours' => 'Setiap hari 08:00 – 17:00'],
    ['name' => 'Cabang Timur',  'address' => 'Jl. Laguna Raya No. 8, Surabaya',    'hours' => 'Minggu 09:00 dan 16:00'],
    ['name' => 'Cabang Selatan','address' => 'Jl. Karang Jaya No. 25, Sidoarjo',   'hours' => 'Minggu 08:30 dan 17:00'],
];

/* ===== Hitung ibadah terdekat (otomatis) ===== */
$now = new DateTime('now');
$next = null; $nextAt = null;
foreach ($ibadah as $s) {
    [$h, $m] = array_map('intval', explode(':', $s['time']));
    $diff = ($s['day'] - (int)$now->format('w') + 7) % 7;
    $cand = (clone $now)->setTime($h, $m)->modify("+{$diff} days");
    if ($cand <= $now) { $cand->modify('+7 days'); }
    if ($nextAt === null || $cand < $nextAt) { $next = $s; $nextAt = $cand; }
}
$nextTs = $nextAt->getTimestamp();

function tgl($d) {
    $bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $t = strtotime($d);
    return date('j', $t) . ' ' . $bln[(int)date('n', $t) - 1] . ' ' . date('Y', $t);
}

function icon($n) {
    $i = [
        'shell'  => '<path d="M12 3c4 0 8 3 8 8 0 4-3 8-8 10-5-2-8-6-8-10 0-5 4-8 8-8zM12 3v18M12 3c-2 3-3 6-3 9s1 6 3 9M12 3c2 3 3 6 3 9s-1 6-3 9"/>',
        'wave'   => '<path d="M2 9c2.5-3 4.5-3 7 0s4.5 3 7 0 4-3 6-1M2 15c2.5-3 4.5-3 7 0s4.5 3 7 0 4-3 6-1"/>',
        'sun'    => '<circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
        'anchor' => '<circle cx="12" cy="5" r="2"/><path d="M12 7v14M7 11h10M4 14c0 4 3.5 7 8 7s8-3 8-7"/>',
        'note'   => '<path d="M9 18V5l11-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="17" cy="16" r="3"/>',
        'heart'  => '<path d="M12 21s-8-5.2-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.8-8 11-8 11z"/>',
    ];
    return '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($i[$n] ?? '') . '</svg>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($church['name']) ?> — <?= e($church['tagline']) ?></title>
<meta name="description" content="<?= e($church['intro']) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --deep:#0A3A4A;      /* laut dalam: teks & footer */
  --lagoon:#12808A;    /* laguna: aksi utama */
  --aqua:#BFE9E6;      /* air dangkal */
  --sky:#E6F6F7;       /* langit pagi */
  --foam:#FBFDFD;      /* buih */
  --sand:#F1E3C8;      /* pasir */
  --sun:#FF8A4C;       /* matahari terbenam: dipakai hemat */
  --ink:#16343D;
  --muted:#4F6F78;
  --radius:18px;
  --font-display:'Bricolage Grotesque',system-ui,sans-serif;
  --font-body:'Figtree',system-ui,sans-serif;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;scroll-padding-top:84px}
body{font-family:var(--font-body);color:var(--ink);background:var(--foam);line-height:1.65;font-size:17px}
a{color:inherit;text-decoration:none}
img,svg{max-width:100%}
:focus-visible{outline:3px solid var(--sun);outline-offset:3px;border-radius:6px}
.wrap{width:min(1120px,92%);margin-inline:auto}
h1,h2,h3{font-family:var(--font-display);line-height:1.1;color:var(--deep)}
h2{font-size:clamp(1.9rem,4vw,2.8rem);font-weight:800;letter-spacing:-.02em}
h3{font-size:1.25rem;font-weight:700}
.lead{color:var(--muted);max-width:56ch;margin-top:.8rem}
section{padding:96px 0}

/* Tombol */
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 1.5rem;border-radius:999px;font-weight:600;font-size:1rem;border:2px solid transparent;cursor:pointer;transition:transform .15s,background .15s,color .15s}
.btn:active{transform:scale(.97)}
.btn-primary{background:var(--lagoon);color:#fff}
.btn-primary:hover{background:var(--deep)}
.btn-ghost{border-color:var(--deep);color:var(--deep)}
.btn-ghost:hover{background:var(--deep);color:#fff}
.btn-sun{background:var(--sun);color:#2a1305}
.btn-sun:hover{background:#ffa06b}

/* Navigasi */
.nav{position:sticky;top:0;z-index:50;background:rgba(251,253,253,.92);backdrop-filter:blur(10px);border-bottom:1px solid #d9eceb}
.nav .wrap{display:flex;align-items:center;justify-content:space-between;height:72px}
.brand{display:flex;align-items:center;gap:.6rem;font-family:var(--font-display);font-weight:800;font-size:1.2rem;color:var(--deep)}
.brand svg{color:var(--lagoon)}
.menu{display:flex;align-items:center;gap:1.8rem;list-style:none}
.menu a{font-weight:500;color:var(--ink)}
.menu a:not(.btn):hover{color:var(--lagoon)}
.burger{display:none;background:none;border:0;padding:.5rem;color:var(--deep);cursor:pointer}

/* Hero */
.hero{position:relative;padding:72px 0 0;background:linear-gradient(180deg,var(--sky) 0%,#d6f0ee 55%,var(--aqua) 100%);overflow:hidden}
.hero .wrap{position:relative;z-index:2;display:grid;grid-template-columns:1.15fr .85fr;gap:3rem;align-items:center;padding-bottom:150px}
.hero h1{font-size:clamp(2.6rem,6.5vw,5rem);font-weight:800;letter-spacing:-.03em}
.hero p{font-size:1.15rem;color:var(--muted);max-width:46ch;margin:1.2rem 0 2rem}
.hero .actions{display:flex;gap:.8rem;flex-wrap:wrap}
.sunball{position:absolute;right:8%;top:60px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle at 40% 35%,#ffd9a3,#ffb36b 60%,var(--sun));opacity:.85;z-index:1}
.next{background:#fff;border-radius:var(--radius);padding:1.6rem;box-shadow:0 18px 40px -18px rgba(10,58,74,.35)}
.next small{color:var(--muted);font-weight:600}
.next h3{font-size:1.7rem;margin:.3rem 0 .2rem}
.next .when{color:var(--lagoon);font-weight:600}
.count{display:flex;gap:.6rem;margin:1.1rem 0 1.3rem}
.count div{flex:1;background:var(--sky);border-radius:12px;text-align:center;padding:.6rem .2rem}
.count b{display:block;font-family:var(--font-display);font-size:1.6rem;color:var(--deep);line-height:1.1}
.count span{font-size:.78rem;color:var(--muted)}
.waves{position:absolute;left:0;bottom:-1px;width:100%;height:150px;z-index:2;overflow:hidden}
.waves svg{position:absolute;bottom:0;left:0;width:200%;height:100%}
.waves .w1{animation:drift 26s linear infinite}
.waves .w2{animation:drift 38s linear infinite reverse;opacity:.7}
@keyframes drift{to{transform:translateX(-50%)}}

/* Ibadah */
.sand{background:var(--sand)}
.head{display:flex;justify-content:space-between;align-items:end;gap:2rem;flex-wrap:wrap;margin-bottom:2.5rem}
.sched{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.2rem}
.svc{background:var(--foam);border-radius:var(--radius);padding:1.5rem;border-left:6px solid var(--lagoon)}
.svc.sun{border-left-color:var(--sun)}
.svc .time{font-family:var(--font-display);font-size:2.3rem;font-weight:800;color:var(--deep);line-height:1}
.svc .day{font-weight:600;color:var(--lagoon);margin:.3rem 0 .8rem}
.svc p{color:var(--muted);font-size:.95rem}
.svc .place{margin-top:.8rem;font-size:.9rem;font-weight:600}

/* Renungan */
.devo{display:grid;grid-template-columns:1.2fr 1fr;gap:2rem}
.feat{background:var(--deep);color:#dff3f2;border-radius:var(--radius);padding:2.4rem;display:flex;flex-direction:column;justify-content:space-between;min-height:340px;position:relative;overflow:hidden}
.feat h3{color:#fff;font-size:clamp(1.8rem,3.5vw,2.5rem);margin:.6rem 0 1rem}
.feat .meta{color:var(--aqua);font-weight:600;font-size:.95rem}
.feat::after{content:"";position:absolute;right:-60px;bottom:-60px;width:220px;height:220px;border-radius:50%;border:2px solid rgba(191,233,230,.25);box-shadow:0 0 0 28px rgba(191,233,230,.08),0 0 0 56px rgba(191,233,230,.05)}
.feat a{color:var(--sun);font-weight:600;position:relative;z-index:1}
.list{display:flex;flex-direction:column;gap:1rem}
.item{padding:1.3rem 0;border-bottom:1px solid #cfe5e4}
.item:first-child{padding-top:0}
.item .meta{color:var(--lagoon);font-weight:600;font-size:.9rem}
.item h3{margin:.3rem 0 .4rem}
.item p{color:var(--muted);font-size:.95rem}

/* Komunitas */
.tint{background:var(--sky)}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.2rem}
.com{display:flex;gap:1.1rem;align-items:flex-start;padding:1.4rem;border-radius:var(--radius);background:#fff;transition:background .2s}
.com:hover{background:var(--aqua)}
.com .ic{flex:none;width:56px;height:56px;border-radius:16px;background:var(--deep);color:var(--aqua);display:grid;place-items:center}
.com p{color:var(--muted);font-size:.95rem;margin-top:.2rem}

/* Lokasi */
.loc{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.2rem}
.pin{padding:1.6rem;border-radius:var(--radius);border:2px solid var(--aqua)}
.pin h3{margin-bottom:.5rem}
.pin p{color:var(--muted);font-size:.95rem}
.pin .hrs{margin-top:.8rem;font-weight:600;color:var(--lagoon)}
.pin a{display:inline-block;margin-top:1rem;font-weight:600;color:var(--deep);border-bottom:2px solid var(--sun)}

/* Giving */
.giving{background:linear-gradient(135deg,var(--lagoon),var(--deep));color:#e4f6f5;text-align:center;padding:88px 0}
.giving h2{color:#fff}
.giving p{margin:1rem auto 2rem;max-width:52ch;color:#cdeceb}

/* Footer */
footer{background:var(--deep);color:#a9cfd0;padding:60px 0 28px}
.foot{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:2.5rem}
footer h4{font-family:var(--font-display);color:#fff;font-size:1.05rem;margin-bottom:.8rem}
footer ul{list-style:none}
footer li{margin:.4rem 0}
footer a:hover{color:#fff}
.copy{margin-top:2.5rem;padding-top:1.2rem;border-top:1px solid rgba(255,255,255,.12);font-size:.9rem}

@media (max-width:860px){
  section{padding:68px 0}
  .menu{display:none;position:absolute;top:72px;left:0;right:0;flex-direction:column;align-items:stretch;gap:0;background:var(--foam);padding:1rem 4%;border-bottom:1px solid #d9eceb}
  .menu.open{display:flex}
  .menu li{padding:.7rem 0}
  .burger{display:block}
  .hero .wrap,.devo,.foot{grid-template-columns:1fr}
  .sunball{width:160px;height:160px;right:-30px;top:30px}
}
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  .waves svg{animation:none!important}
}
</style>
</head>
<body>

<header class="nav">
  <div class="wrap">
    <a href="#" class="brand">
      <?= icon('wave') ?>
      <?= e($church['name']) ?>
    </a>
    <button class="burger" id="burger" aria-label="Buka menu" aria-expanded="false">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
    <ul class="menu" id="menu">
      <?php foreach ($nav as $label => $href): ?>
        <li><a href="<?= e($href) ?>"><?= e($label) ?></a></li>
      <?php endforeach; ?>
      <li><a class="btn btn-primary" href="<?= e($church['live']) ?>">Ibadah online</a></li>
    </ul>
  </div>
</header>

<main>
<!-- HERO -->
<section class="hero" style="padding-bottom:0">
  <div class="sunball" aria-hidden="true"></div>
  <div class="wrap">
    <div>
      <h1><?= e($church['tagline']) ?></h1>
      <p><?= e($church['intro']) ?></p>
      <div class="actions">
        <a class="btn btn-primary" href="#ibadah">Lihat jadwal ibadah</a>
        <a class="btn btn-ghost" href="#lokasi">Cari lokasi terdekat</a>
      </div>
    </div>

    <aside class="next" aria-label="Ibadah berikutnya">
      <small>Ibadah berikutnya</small>
      <h3><?= e($next['name']) ?></h3>
      <div class="when"><?= e($hari[$next['day']]) ?>, <?= e($nextAt->format('j M')) ?> · <?= e($next['time']) ?> WIB</div>
      <div class="count" id="count" data-ts="<?= $nextTs ?>">
        <div><b id="cd">0</b><span>hari</span></div>
        <div><b id="ch">0</b><span>jam</span></div>
        <div><b id="cm">0</b><span>menit</span></div>
      </div>
      <a class="btn btn-sun" href="<?= e($church['live']) ?>" style="width:100%;justify-content:center">Tonton siaran langsung</a>
    </aside>
  </div>

  <div class="waves" aria-hidden="true">
    <svg class="w2" viewBox="0 0 2400 150" preserveAspectRatio="none"><path fill="#12808A" d="M0 70c200-50 400-50 600 0s400 50 600 0 400-50 600 0 400 50 600 0v80H0z"/></svg>
    <svg class="w1" viewBox="0 0 2400 150" preserveAspectRatio="none"><path fill="#F1E3C8" d="M0 100c200-40 400-40 600 0s400 40 600 0 400-40 600 0 400 40 600 0v50H0z"/></svg>
  </div>
</section>

<!-- JADWAL IBADAH -->
<section class="sand" id="ibadah">
  <div class="wrap">
    <div class="head">
      <div>
        <h2>Jadwal ibadah</h2>
        <p class="lead">Semua ibadah terbuka untuk umum. Tidak perlu daftar, langsung datang saja.</p>
      </div>
      <a class="btn btn-ghost" href="<?= e($church['live']) ?>">Ibadah online</a>
    </div>
    <div class="sched">
      <?php foreach ($ibadah as $s): ?>
        <article class="svc <?= $s['day'] === 0 ? '' : 'sun' ?>">
          <div class="time"><?= e($s['time']) ?></div>
          <div class="day"><?= e($hari[$s['day']]) ?></div>
          <h3><?= e($s['name']) ?></h3>
          <p><?= e($s['note']) ?></p>
          <div class="place"><?= e($s['place']) ?></div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- RENUNGAN -->
<section id="renungan">
  <div class="wrap">
    <div class="head">
      <div>
        <h2>Renungan harian</h2>
        <p class="lead">Beberapa menit firman untuk mengawali harimu.</p>
      </div>
      <a class="btn btn-ghost" href="#">Semua renungan</a>
    </div>
    <div class="devo">
      <?php $f = $renungan[0]; ?>
      <article class="feat">
        <div>
          <div class="meta"><?= e(tgl($f['date'])) ?> · <?= e($f['verse']) ?></div>
          <h3><?= e($f['title']) ?></h3>
          <p><?= e($f['excerpt']) ?></p>
        </div>
        <a href="#">Baca selengkapnya</a>
      </article>
      <div class="list">
        <?php foreach (array_slice($renungan, 1) as $r): ?>
          <article class="item">
            <div class="meta"><?= e(tgl($r['date'])) ?> · <?= e($r['verse']) ?></div>
            <h3><a href="#"><?= e($r['title']) ?></a></h3>
            <p><?= e($r['excerpt']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- KOMUNITAS -->
<section class="tint" id="komunitas">
  <div class="wrap">
    <div class="head">
      <div>
        <h2>Temukan komunitasmu</h2>
        <p class="lead">Bertumbuh itu lebih mudah kalau tidak sendirian.</p>
      </div>
    </div>
    <div class="grid">
      <?php foreach ($komunitas as $c): ?>
        <a class="com" href="#">
          <span class="ic"><?= icon($c['icon']) ?></span>
          <span>
            <h3><?= e($c['name']) ?></h3>
            <p><?= e($c['desc']) ?></p>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- LOKASI -->
<section id="lokasi">
  <div class="wrap">
    <div class="head">
      <div>
        <h2>Lokasi gereja</h2>
        <p class="lead">Pilih lokasi yang paling dekat denganmu.</p>
      </div>
    </div>
    <div class="loc">
      <?php foreach ($lokasi as $l): ?>
        <article class="pin">
          <h3><?= e($l['name']) ?></h3>
          <p><?= e($l['address']) ?></p>
          <div class="hrs"><?= e($l['hours']) ?></div>
          <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($l['name'] . ' ' . $l['address']) ?>" target="_blank" rel="noopener">Buka di Google Maps</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- GIVING -->
<section class="giving" id="giving">
  <div class="wrap">
    <h2>Memberi dengan sukacita</h2>
    <p>Persembahanmu mendukung pelayanan gereja, bantuan untuk sesama, dan pekerjaan misi.</p>
    <a class="btn btn-sun" href="<?= e($church['giving']) ?>">Beri persembahan</a>
  </div>
</section>
</main>

<footer>
  <div class="wrap">
    <div class="foot">
      <div>
        <a href="#" class="brand" style="color:#fff"><?= icon('wave') ?> <?= e($church['name']) ?></a>
        <p style="margin-top:.8rem;max-width:36ch"><?= e($church['tagline']) ?>.</p>
      </div>
      <div>
        <h4>Menu</h4>
        <ul>
          <?php foreach ($nav as $label => $href): ?>
            <li><a href="<?= e($href) ?>"><?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4>Hubungi kami</h4>
        <ul>
          <li><a href="mailto:<?= e($church['email']) ?>"><?= e($church['email']) ?></a></li>
          <li><a href="tel:<?= e(preg_replace('/\s+/', '', $church['phone'])) ?>"><?= e($church['phone']) ?></a></li>
        </ul>
      </div>
    </div>
    <div class="copy">© <?= date('Y') ?> <?= e($church['name']) ?>. Semua hak dilindungi.</div>
  </div>
</footer>

<script>
// Menu mobile
const burger = document.getElementById('burger'), menu = document.getElementById('menu');
burger.addEventListener('click', () => {
  const open = menu.classList.toggle('open');
  burger.setAttribute('aria-expanded', open);
});
menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
  menu.classList.remove('open'); burger.setAttribute('aria-expanded', false);
}));

// Hitung mundur ibadah berikutnya
(function () {
  const target = parseInt(document.getElementById('count').dataset.ts, 10) * 1000;
  const d = document.getElementById('cd'), h = document.getElementById('ch'), m = document.getElementById('cm');
  function tick() {
    let s = Math.max(0, Math.floor((target - Date.now()) / 1000));
    d.textContent = Math.floor(s / 86400);
    h.textContent = Math.floor(s % 86400 / 3600);
    m.textContent = Math.floor(s % 3600 / 60);
  }
  tick(); setInterval(tick, 30000);
})();
</script>
</body>
</html>