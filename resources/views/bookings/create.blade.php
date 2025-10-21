@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0">
        <h5 class="fw-bold mb-0">Ruang Meeting</h5>
        <small class="text-muted">Ruang Meeting > Pesan Ruangan</small>
    </div>
    <div class="card-body">

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <h6 class="fw-bold mb-3">Informasi Ruang Meeting</h6>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label for="unit_id" class="form-label">Unit</label>
                    <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
                        <option value="">Pilih Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->kode_unit }} - {{ $unit->nama_unit }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="meeting_room_id" class="form-label">Ruang Meeting</label>
                    <select class="form-select @error('meeting_room_id') is-invalid @enderror" id="meeting_room_id" name="meeting_room_id" required>
                        <option value="">Pilih Ruang Meeting</option>
                        @foreach($meetingRooms as $room)
                        <option value="{{ $room->id }}" 
                            data-capacity="{{ $room->kapasitas }}"
                            {{ old('meeting_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->nama_ruang }}
                        </option>
                        @endforeach
                    </select>
                    @error('meeting_room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Kapasitas</label>
                        <input type="text" id="kapasitas" class="form-control" readonly value="0" disabled>
                    </div>
            </div>

            <h6 class="fw-bold mb-3 mt-4">Informasi Rapat</h6>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label for="tanggal_rapat" class="form-label">Tanggal Rapat *</label>
                    <input type="date" class="form-control @error('tanggal_rapat') is-invalid @enderror" 
                        id="tanggal_rapat" name="tanggal_rapat" value="{{ old('tanggal_rapat', date('Y-m-d')) }}" required>
                    @error('tanggal_rapat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', '08:00') }}" required>
                    @error('waktu_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', '17:00') }}" required>
                    @error('waktu_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 mb-3">
                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" 
                        class="form-control @error('jumlah_peserta') is-invalid @enderror" 
                        placeholder="Masukkan jumlah peserta" 
                        value="{{ old('jumlah_peserta') }}" min="1" required>
                    @error('jumlah_peserta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>

            <div class="mb-3">
                    <label class="form-label">Jenis Konsumsi</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="snack_siang_checkbox" disabled>
                        <label class="form-check-label" for="snack_siang_checkbox">Snack Siang</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="makan_siang_checkbox" disabled>
                        <label class="form-check-label" for="makan_siang_checkbox">Makan Siang</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="snack_sore_checkbox" disabled>
                        <label class="form-check-label" for="snack_sore_checkbox">Snack Sore</label>
                    </div>
                    
                    <!-- Hidden fields untuk backend -->
                    <input type="hidden" name="snack_siang" id="snack_siang" value="0">
                    <input type="hidden" name="makan_siang" id="makan_siang" value="0">
                    <input type="hidden" name="snack_sore" id="snack_sore" value="0">
                </div>

            <div class="mb-4">
                <label class="form-label">Nominal Konsumsi</label>
                <div class="input-group">
                    <span class="input-group-text">Rp.</span>
                    <input type="text" class="form-control" id="nominal_konsumsi" value=" 0" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-danger me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit_id');
    const meetingRoomSelect = document.getElementById('meeting_room_id');
    const kapasitasInput = document.getElementById('kapasitas');
    const jumlahPesertaInput = document.getElementById('jumlah_peserta');
    const waktuMulaiSelect = document.getElementById('waktu_mulai');
    const waktuSelesaiSelect = document.getElementById('waktu_selesai');
    const nominalKonsumsiInput = document.getElementById('nominal_konsumsi');

    // Checkbox untuk konsumsi
    const snackSiangCheckbox = document.getElementById('snack_siang_checkbox');
    const makanSiangCheckbox = document.getElementById('makan_siang_checkbox');
    const snackSoreCheckbox = document.getElementById('snack_sore_checkbox');
    
    // Hidden fields untuk backend
    const snackSiangHidden = document.getElementById('snack_siang');
    const makanSiangHidden = document.getElementById('makan_siang');
    const snackSoreHidden = document.getElementById('snack_sore');

    // Update kapasitas ketika ruang meeting dipilih
    meetingRoomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacity') || 0;
        kapasitasInput.value = capacity;
        
        if (capacity) {
            jumlahPesertaInput.max = capacity;
        }
        
        // Hitung ulang konsumsi
        calculateConsumption();
    });

    // Validasi waktu selesai harus setelah waktu mulai
    waktuMulaiSelect.addEventListener('change', function() {
        const startTime = this.value;
        const endOptions = waktuSelesaiSelect.options;
        
        // Reset semua opsi
        for (let i = 0; i < endOptions.length; i++) {
            endOptions[i].style.display = '';
        }
        
        // Sembunyikan opsi yang tidak valid
        if (startTime) {
            for (let i = 0; i < endOptions.length; i++) {
                if (endOptions[i].value && endOptions[i].value <= startTime) {
                    endOptions[i].style.display = 'none';
                }
            }
            
            // Reset nilai jika tidak valid
            if (waktuSelesaiSelect.value && waktuSelesaiSelect.value <= startTime) {
                waktuSelesaiSelect.value = '';
            }
        }
        
        calculateConsumption();
    });

    waktuSelesaiSelect.addEventListener('change', calculateConsumption);

    // Hitung konsumsi otomatis berdasarkan waktu
    function calculateConsumption() {
        const startTime = waktuMulaiSelect.value;
        const endTime = waktuSelesaiSelect.value;
        const peserta = parseInt(jumlahPesertaInput.value) || 0;

        if (!startTime || !endTime) return;

        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        const snackTime = new Date(`2000-01-01T11:00:00`);
        const lunchStart = new Date(`2000-01-01T11:00:00`);
        const lunchEnd = new Date(`2000-01-01T14:00:00`);
        const snackAfternoon = new Date(`2000-01-01T14:00:00`);

        let snackSiang = false;
        let makanSiang = false;
        let snackSore = false;

        // Rules konsumsi sesuai ketentuan
        // Meeting mulai sebelum jam 11:00 - Snack Siang
        if (start < snackTime) {
            snackSiang = true;
        }
        
        // Meeting antara jam 11:00-14:00 - Makan Siang
        if (start <= lunchEnd && end >= lunchStart) {
            makanSiang = true;
        }
        
        // Meeting di atas jam 14:00 - Snack Sore
        if (end > snackAfternoon) {
            snackSore = true;
        }

        // Update checkbox dan hidden fields
        snackSiangCheckbox.checked = snackSiang;
        makanSiangCheckbox.checked = makanSiang;
        snackSoreCheckbox.checked = snackSore;
        
        snackSiangHidden.value = snackSiang ? '1' : '0';
        makanSiangHidden.value = makanSiang ? '1' : '0';
        snackSoreHidden.value = snackSore ? '1' : '0';

        // Hitung nominal
        const snackPrice = 20000;
        const lunchPrice = 30000;
        let totalPerPerson = 0;

        if (snackSiang) totalPerPerson += snackPrice;
        if (makanSiang) totalPerPerson += lunchPrice;
        if (snackSore) totalPerPerson += snackPrice;

        const totalNominal = totalPerPerson * peserta;
        nominalKonsumsiInput.value = `${totalNominal.toLocaleString('id-ID')}`;
    }

    // Event listeners untuk perhitungan real-time
    jumlahPesertaInput.addEventListener('input', calculateConsumption);

    // Inisialisasi awal
    const selectedRoom = meetingRoomSelect.options[meetingRoomSelect.selectedIndex];
    if (selectedRoom && selectedRoom.value) {
        const capacity = selectedRoom.getAttribute('data-capacity') || 0;
        kapasitasInput.value = capacity;
        jumlahPesertaInput.max = capacity;
    }

    // Validasi waktu awal
    if (waktuMulaiSelect.value) {
        waktuMulaiSelect.dispatchEvent(new Event('change'));
    }

    // Hitung konsumsi pertama kali
    calculateConsumption();
});
</script>
@endpush