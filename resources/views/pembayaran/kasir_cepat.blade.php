@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="mb-0">Kasir Cepat Pembayaran</h4>
        <p class="text-muted small mb-0">Cari siswa, centang tagihan, masukkan uang pembayaran, dan selesaikan transaksi dengan sekali klik.</p>
    </div>
</div>

<div class="row">
    <!-- Kolom Pencarian & Input Uang -->
    <div class="col-md-4">
        <!-- Card Cari Siswa -->
        <div class="card p-4 mb-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3">1. Cari Siswa</h5>
            <div class="mb-3">
                <select class="form-select select2-siswa" id="select_siswa" style="width: 100%;">
                </select>
            </div>
            
            <!-- Area Ringkasan Siswa (Hidden by default) -->
            <div id="detail_siswa" style="display: none;" class="mt-3 bg-light p-3 rounded">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Nama:</span>
                    <strong id="info_nama" class="text-dark small"></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">NISN:</span>
                    <strong id="info_nisn" class="text-dark small"></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Kelas:</span>
                    <strong id="info_kelas" class="text-dark small"></strong>
                </div>
            </div>
        </div>

        <!-- Card Kalkulator Kasir -->
        <div class="card p-4 border-0 shadow-sm" id="card_kalkulator" style="display: none;">
            <h5 class="fw-bold mb-3">3. Kalkulator Kasir</h5>
            
            <div class="mb-3">
                <label class="form-label text-muted small">Total Tagihan Terpilih</label>
                <div class="h3 fw-bold text-primary mb-0">Rp <span id="total_tagihan_label">0</span></div>
                <input type="hidden" id="total_tagihan_val" value="0">
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small">Uang Diterima (Rp)</label>
                <input type="text" id="uang_diterima" class="form-control form-control-lg bg-light border-0 fw-bold" placeholder="Masukkan jumlah uang...">
            </div>
            
            <div class="mb-4">
                <label class="form-label text-muted small">Uang Kembalian</label>
                <div class="h4 fw-bold text-success mb-0">Rp <span id="kembalian_label">0</span></div>
            </div>
            
            <button type="button" id="btn_submit_bayar" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm" disabled>
                <i class="fa-solid fa-receipt me-2"></i> Proses Pembayaran
            </button>
        </div>
    </div>

    <!-- Kolom Daftar Tagihan -->
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm min-h-100">
            <h5 class="fw-bold mb-3">2. Pilih Tagihan yang Ingin Dibayar</h5>
            
            <!-- Empty state -->
            <div id="tunggakan_empty" class="text-center py-5">
                <img src="https://illustrations.popsy.co/gray/success.svg" alt="Success illustration" style="width: 150px;" class="mb-3">
                <p class="text-muted">Cari dan pilih siswa terlebih dahulu untuk melihat daftar tagihan aktif.</p>
            </div>

            <!-- Table area (Hidden by default) -->
            <div id="tunggakan_area" style="display: none;">
                <form id="form_kasir_cepat" action="{{ route('pembayaran.proses-kasir-cepat') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_siswa" id="submit_id_siswa">
                    
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50px">
                                        <input type="checkbox" id="check_all_tagihan" class="form-check-input">
                                    </th>
                                    <th>Nama Tagihan</th>
                                    <th>Tanggal Diterbitkan</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody id="tunggakan_tbody">
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Select2 */
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
        font-size: 1rem;
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
    $(document).ready(function() {
        // Init Select2 AJAX
        $('#select_siswa').select2({
            theme: 'bootstrap-5',
            placeholder: "🔍 Cari NISN atau Nama Siswa...",
            allowClear: true,
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

        // Event: Siswa Selected
        $('#select_siswa').on('select2:select', function(e) {
            let siswaId = e.params.data.id;
            loadTunggakan(siswaId);
        });

        // Event: Siswa Cleared
        $('#select_siswa').on('select2:clear', function() {
            resetKasir();
        });

        // Handle Checkboxes
        $(document).on('change', '.checkbox-tagihan, #check_all_tagihan', function() {
            if ($(this).attr('id') === 'check_all_tagihan') {
                $('.checkbox-tagihan').prop('checked', $(this).prop('checked'));
            } else {
                let allChecked = $('.checkbox-tagihan:checked').length === $('.checkbox-tagihan').length;
                $('#check_all_tagihan').prop('checked', allChecked);
            }
            hitungTotal();
        });

        // Format Rupiah Input Keyup
        $('#uang_diterima').on('keyup', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatRupiahVal(value));
            hitungKembalian();
        });

        // Submit Button Click
        $('#btn_submit_bayar').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda yakin ingin memproses pelunasan untuk tagihan terpilih ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5196fe',
                cancelButtonColor: '#6e6e6e',
                confirmButtonText: 'Ya, Bayar Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form_kasir_cepat').submit();
                }
            });
        });

        function loadTunggakan(siswaId) {
            Swal.showLoading();
            $.ajax({
                url: `/api/siswa/${siswaId}/tunggakan`,
                type: 'GET',
                success: function(response) {
                    Swal.close();
                    $('#submit_id_siswa').val(siswaId);
                    
                    // Show Siswa Info
                    $('#info_nama').text(response.siswa.nama);
                    $('#info_nisn').text(response.siswa.nisn);
                    $('#info_kelas').text(response.siswa.kelas);
                    $('#detail_siswa').slideDown();

                    // Render Table
                    let tbody = $('#tunggakan_tbody');
                    tbody.empty();

                    if (response.tunggakan.length === 0) {
                        $('#tunggakan_empty').html(`
                            <img src="https://illustrations.popsy.co/gray/success.svg" alt="Success illustration" style="width: 150px;" class="mb-3">
                            <h6 class="text-success fw-bold">Siswa Bebas Tagihan!</h6>
                            <p class="text-muted">Tidak ditemukan tunggakan aktif untuk siswa ini.</p>
                        `).show();
                        $('#tunggakan_area').hide();
                        $('#card_kalkulator').hide();
                    } else {
                        response.tunggakan.forEach(function(item) {
                            tbody.append(`
                                <tr>
                                    <td>
                                        <input type="checkbox" name="pembayaran_ids[]" value="${item.id_pembayaran}" data-nominal="${item.nominal}" class="form-check-input checkbox-tagihan">
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark">${item.nama_tagihan}</span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">${item.tanggal_tagihan}</span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp ${item.nominal_format}
                                    </td>
                                </tr>
                            `);
                        });
                        
                        $('#check_all_tagihan').prop('checked', false);
                        $('#tunggakan_empty').hide();
                        $('#tunggakan_area').show();
                        $('#card_kalkulator').show();
                        hitungTotal();
                    }
                },
                error: function() {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengambil data tagihan siswa.', 'error');
                    resetKasir();
                }
            });
        }

        function hitungTotal() {
            let total = 0;
            $('.checkbox-tagihan:checked').each(function() {
                total += parseFloat($(this).data('nominal'));
            });

            $('#total_tagihan_val').val(total);
            $('#total_tagihan_label').text(formatNominalFormat(total));
            
            if (total > 0) {
                $('#btn_submit_bayar').prop('disabled', false);
            } else {
                $('#btn_submit_bayar').prop('disabled', true);
            }

            hitungKembalian();
        }

        function hitungKembalian() {
            let total = parseFloat($('#total_tagihan_val').val()) || 0;
            let uangDiterimaRaw = $('#uang_diterima').val().replace(/[^0-9]/g, '');
            let uangDiterima = parseFloat(uangDiterimaRaw) || 0;

            let kembalian = uangDiterima - total;
            
            if (kembalian < 0) {
                $('#kembalian_label').text('0').removeClass('text-success').addClass('text-danger');
                $('#btn_submit_bayar').prop('disabled', true);
            } else {
                $('#kembalian_label').text(formatNominalFormat(kembalian)).removeClass('text-danger').addClass('text-success');
                if (total > 0) {
                    $('#btn_submit_bayar').prop('disabled', false);
                }
            }
        }

        function resetKasir() {
            $('#detail_siswa').hide();
            $('#tunggakan_empty').html(`
                <img src="https://illustrations.popsy.co/gray/success.svg" alt="Success illustration" style="width: 150px;" class="mb-3">
                <p class="text-muted">Cari dan pilih siswa terlebih dahulu untuk melihat daftar tagihan aktif.</p>
            `).show();
            $('#tunggakan_area').hide();
            $('#card_kalkulator').hide();
            $('#uang_diterima').val('');
            $('#total_tagihan_val').val(0);
            $('#total_tagihan_label').text('0');
            $('#kembalian_label').text('0');
        }

        // Helper Format Numbers
        function formatNominalFormat(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatRupiahVal(angka) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
        
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
        
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }
    });
</script>
@endpush
@endsection
