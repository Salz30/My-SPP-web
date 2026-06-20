<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - MySPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #1b1d20;
        }
        .main-card {
            border: none;
            border-radius: 24px;
            box-shadow: rgba(0, 0, 0, 0.04) 0px 4px 16px;
            background-color: #ffffff;
        }
        .card-header-custom {
            background-color: transparent;
            border-bottom: 1px solid #e1dfd8;
            padding: 32px 32px 16px 32px;
        }
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #6e6e6e;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 12.8px;
            padding: 12px 16px;
            border: 1px solid #a3a3a3;
            font-size: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #5196fe;
            box-shadow: 0 0 0 3px rgba(81, 150, 240, 0.15);
        }
        .btn-custom {
            border-radius: 9999px;
            padding: 12px 28px;
            font-weight: 500;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-primary-custom {
            background-color: #5196fe;
            border-color: #5196fe;
            color: #ffffff;
        }
        .btn-primary-custom:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }
        .btn-success-custom {
            background-color: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }
        .btn-success-custom:hover {
            background-color: #059669;
            border-color: #059669;
            color: #ffffff;
        }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>

    <div class="container py-5" style="max-width: 1000px;">
        
        <div class="page-header d-flex align-items-center gap-3 mb-4">
            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                <i class="fa-solid fa-chart-line fa-xl"></i>
            </div>
            <div>
                <h1 class="mb-1 text-dark">Laporan Keuangan</h1>
                <p class="text-muted mb-0">Filter dan unduh data rekapitulasi pembayaran SPP siswa.</p>
            </div>
        </div>

        <div class="card main-card">
            <div class="card-header-custom">
                <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-sliders me-2 text-secondary"></i> Parameter Laporan</h5>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="{{ route('laporan.cetak') }}" target="_blank">
                    @csrf

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="tanggal_awal" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_akhir" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="status_bayar" class="form-label">Status Pembayaran</label>
                            <select name="status_bayar" id="status_bayar" class="form-select">
                                <option value="">-- Semua Status --</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_kelas" class="form-label">Kelas Spesifik</label>
                            <select name="id_kelas" id="id_kelas" class="form-select">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-4">
                        <a href="{{ route('dashboard') }}" class="btn text-secondary px-0 fw-medium">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-custom btn-primary-custom">
                                <i class="fa-solid fa-print"></i> Cetak ke Layar
                            </button>

                            <button type="submit" formaction="{{ route('laporan.pdf') }}" class="btn btn-custom" style="background-color: #ef4444; color: white; border-color: #ef4444;">
                                <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                            </button>

                            <button type="submit" formaction="{{ route('laporan.excel') }}" class="btn btn-custom btn-success-custom">
                                <i class="fa-solid fa-file-excel"></i> Unduh Excel
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>