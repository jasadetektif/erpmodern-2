<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Arkavera ERP - Selamat Datang</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.3/vanilla-tilt.min.js"></script>

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #0f172a;
      color: white;
      overflow-x: hidden;
    }

    /* Splash screen */
    #splash {
      position: fixed;
      background: linear-gradient(135deg, #065f46, #0f766e);
      color: white;
      z-index: 9999;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      font-size: 2rem;
      animation: fadeOut 2s 2s forwards;
    }
    @keyframes fadeOut {
      to { opacity: 0; visibility: hidden; }
    }

    .hero-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      background: radial-gradient(circle at top, #1a2e1f, #041d13);
    }

    .card-floating {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.15);
      backdrop-filter: blur(10px);
      border-radius: 1rem;
      padding: 2rem;
      max-width: 700px;
      text-align: center;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      z-index: 2;
    }

    .btn-green {
      background-color: #22c55e;
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 8px;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .btn-green:hover {
      background-color: #16a34a;
    }

    .scroll-indicator {
      position: absolute;
      bottom: 20px;
      font-size: 2rem;
      animation: bounce 2s infinite;
      z-index: 3;
      color: #22c55e;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(8px); }
    }

    .section-content {
      background: linear-gradient(to bottom, #0f766e, #022c22);
      color: #e6fffa;
      padding: 4rem 2rem;
    }

    .section-content h2 {
      color: #34d399;
      font-weight: bold;
      margin-bottom: 2.5rem;
      text-align: center;
    }

    .feature-card {
      transition: all 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 16px rgba(34, 197, 94, 0.4);
    }
  </style>
</head>
<body>

<!-- 🔊 Audio -->
<audio id="intro-audio" src="{{ asset('audio/arkavera-intro.mp3') }}"></audio>

<!-- 🚀 Splash Screen -->
<div id="splash">
  <div>Arkavera ERP</div>
  <div class="spinner-border text-light mt-3" role="status"></div>
</div>

<!-- 🎯 Hero -->
<div class="hero-container">
  <div class="card-floating" data-tilt data-tilt-max="10" data-tilt-speed="400">
    <h2>Selamat Datang di <span class="text-success">Arkavera</span></h2>
    <p class="mt-3 fst-italic">"Membangun Masa Depan, Satu Proyek Sekaligus."</p>
    <p class="mt-3">ERP modern untuk manajemen proyek, keuangan, dan SDM perusahaan konstruksi Anda.</p>
    <a href="{{ route('login') }}" class="btn btn-green mt-4">
      <i class="bi bi-box-arrow-in-right"></i> Masuk
    </a>
  </div>
  <div class="scroll-indicator text-center w-100">
    <i class="bi bi-chevron-double-down"></i>
  </div>
</div>

<!-- 📄 Rekapitulasi & Roadmap -->
<section class="section-content">
  <div class="container">
    <h2>Rekapitulasi Final & Peta Jalan Pengembangan</h2>
    <div class="row g-4">

      <!-- Pilar -->
      @foreach([
        ['Manajemen Proyek', 'bi-diagram-3-fill', 'WBS, Gantt Chart, Tim, Dokumen, Keuangan Proyek.'],
        ['Penjualan & Pendapatan', 'bi-coin', 'Quotation, Faktur Klien, Pembayaran Otomatis & PDF.'],
        ['Pengadaan & Vendor', 'bi-truck', 'PR, PO, Stok, Penerimaan, Vendor, Pembayaran.'],
        ['SDM & Gaji', 'bi-people', 'Karyawan, Absensi Proyek, Slip Gaji Otomatis.'],
        ['Laporan & Analitik', 'bi-bar-chart-fill', 'Laba Rugi, Cash Flow, Aging Report, PDF Export.'],
        ['Platform & Otomasi', 'bi-gear-wide-connected', 'Dashboard, RBAC, Notifikasi, Multi Bahasa.']
      ] as [$title, $icon, $desc])
      <div class="col-md-4">
        <div class="feature-card p-4 bg-dark bg-opacity-25 border-start border-success border-4 rounded shadow-sm h-100">
          <h5><i class="bi {{ $icon }} text-success me-2"></i>{{ $title }}</h5>
          <p class="small mb-0">{{ $desc }}</p>
        </div>
      </div>
      @endforeach

      <!-- Roadmap -->
      <div class="col-12 mt-5">
        <h4 class="text-white mb-4">🚀 Roadmap Pengembangan</h4>
      </div>

      <div class="col-md-6">
        <div class="feature-card p-4 bg-dark bg-opacity-25 border-start border-warning border-4 rounded shadow-sm h-100">
          <h6 class="fw-bold"><i class="bi bi-calendar-check me-2 text-warning"></i>Prioritas Utama</h6>
          <p class="small mb-0">Modul Kontrak Klien, Termin Pembayaran, & Dokumen Legal Proyek.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="feature-card p-4 bg-dark bg-opacity-25 border-start border-info border-4 rounded shadow-sm h-100">
          <h6 class="fw-bold"><i class="bi bi-lightbulb me-2 text-info"></i>Pengembangan Menengah</h6>
          <ul class="small mb-0 ps-3">
            <li>Rekap Absensi Proyek</li>
            <li>Laporan Neraca</li>
            <li>Notifikasi Event Tambahan</li>
            <li>Pengaturan Sistem Modular</li>
          </ul>
        </div>
      </div>

      <div class="col-12">
        <div class="feature-card p-4 mt-4 bg-dark bg-opacity-25 border border-success rounded shadow-sm">
          <h6 class="fw-bold"><i class="bi bi-rocket-takeoff me-2 text-success"></i>Visi Jangka Panjang</h6>
          <ul class="small ps-3 mb-0">
            <li>Portal Klien & Subkontraktor</li>
            <li>Fitur OCR AI untuk Faktur</li>
            <li>Mobile App Offline</li>
            <li>Integrasi Akuntansi & Perbankan</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- 🔁 Autoplay JS -->
<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      const audio = document.getElementById('intro-audio');
      audio.play().catch(err => console.log("Autoplay blocked:", err));
    }, 2000);
  });
</script>

</body>
</html>
