@extends('layouts.admin')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0">Bebankan Tagihan ke Siswa</h4>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Pilih Tagihan <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0 @error('id_tagihan') is-invalid @enderror" name="id_tagihan" required>
                        <option value="" disabled selected>-- Pilih Tagihan --</option>
                        @foreach($tagihan as $t)
                            <option value="{{ $t->id_tagihan }}" {{ old('id_tagihan') == $t->id_tagihan ? 'selected' : '' }}>
                                {{ $t->nama_tagihan }} (Rp {{ number_format($t->kategori->nominal_default ?? 0, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Metode Pembebanan Tagihan <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0" name="tipe_pembebanan" id="tipe_pembebanan" onchange="toggleFilter()" required>
                        <option value="siswa" selected>Per Siswa (Individu)</option>
                        <option value="kelas">Berdasarkan Kelas (Kolektif)</option>
                        <option value="semua">Bebankan ke Seluruh Siswa Sekolah</option>
                    </select>
                </div>

                <div class="mb-4" id="box_siswa">
                    <label class="form-label fw-bold text-dark">Cari Siswa <span class="text-danger">*</span></label>
                    <select class="form-select select2-siswa" name="id_siswa" id="input_siswa">
                        </select>
                    @error('id_siswa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4" id="box_kelas" style="display: none;">
                    <label class="form-label fw-bold text-dark">Pilih Kelas <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg bg-light border-0" name="id_kelas" id="input_kelas">
                        <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    @error('id_kelas') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info mt-3 shadow-sm border-0" id="info_semua" style="display: none;">
                    <i class="fa-solid fa-info-circle me-2"></i> Sistem akan secara otomatis membebankan tagihan ini kepada <strong>seluruh siswa</strong> yang ada di dalam database.
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Tetapkan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Desain kustom Select2 agar menyatu dengan Bootstrap 5 */
    .select2-container .select2-selection--single {
        height: 48px !important;
        background-color: #f8f9fa !important; 
        border: none !important;
        border-radius: 0.5rem !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #5d5d5d !important;
        padding-left: 1rem;
        font-size: 1.125rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 10px !important;
    }
    .select2-dropdown {
        border: 1px solid #e7e7e7 !important;
        border-radius: 0.5rem !important;
    }
</style>

@push('scripts')
<script>
    // Fungsi untuk menyembunyikan/menampilkan div berdasarkan pilihan dropdown
    function toggleFilter() {
        let metode = document.getElementById('tipe_pembebanan').value;
        let boxSiswa = document.getElementById('box_siswa');
        let boxKelas = document.getElementById('box_kelas');
        let infoSemua = document.getElementById('info_semua');
        
        let inputSiswa = document.getElementById('input_siswa');
        let inputKelas = document.getElementById('input_kelas');

        // Sembunyikan semuanya terlebih dahulu
        boxSiswa.style.display = 'none';
        boxKelas.style.display = 'none';
        infoSemua.style.display = 'none';
        
        // Cabut status required agar form bisa disubmit meskipun elemen disembunyikan
        inputSiswa.required = false;
        inputKelas.required = false;

        // Munculkan hanya elemen yang dibutuhkan dan kembalikan status required-nya
        if (metode === 'siswa') {
            boxSiswa.style.display = 'block';
            inputSiswa.required = true;
        } else if (metode === 'kelas') {
            boxKelas.style.display = 'block';
            inputKelas.required = true;
        } else if (metode === 'semua') {
            infoSemua.style.display = 'block';
        }
    }

    $(document).ready(function() {
        // Inisialisasi Select2 dengan AJAX
        $('.select2-siswa').select2({
            theme: 'bootstrap-5',
            placeholder: "🔍 Ketik Nama atau NISN minimal 2 huruf...",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('api.siswa.search') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 2
        });

        // Panggil fungsi toggle saat halaman PERTAMA KALI diload
        // Ini memastikan tampilan langsung benar jika admin mereload halaman
        toggleFilter();
    });
</script>
@endpush
@endsection