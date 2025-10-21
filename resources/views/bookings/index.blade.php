@extends('layouts.app')

@section('content')
<style>
.text-gradient {
    background: linear-gradient(90deg, #00A2B9, #035B71);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 600;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Ruang Meeting</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active">
                <a href="{{ route('bookings.index') }}" class="text-success">Ruang Meeting</a>
            </li>
        </ol>
        </nav>
    </div>
    <a href="{{ route('bookings.create') }}" class="btn btn-gradient-pln">
        <i class="bi bi-plus-lg"></i> Pesan Ruangan
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Ruang Meeting</th>
                                    <th>Unit</th>
                                    <th>Waktu</th>
                                    <th>Peserta</th>
                                    <th>Konsumsi</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    @if(auth()->user()->role !== 'pegawai')
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon-date rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-calendar-event text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_rapat)->format('d M Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($booking->tanggal_rapat)->format('l') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $booking->meetingRoom->nama_ruang }}</div>
                                    </td>

                                    <td>
                                        <small class="text-muted">{{ $booking->meetingRoom->unit->kode_unit }}</small>
                                    </td>

                                    <td>
                                        <div class="time-chip">
                                            <i class="bi bi-clock text-primary me-1"></i>
                                            {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $booking->jumlah_peserta }} orang</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($booking->snack_siang)
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 consumption-badge">Snack Siang</span>
                                            @endif
                                            @if($booking->makan_siang)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 consumption-badge">Makan Siang</span>
                                            @endif
                                            @if($booking->snack_sore)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 consumption-badge">Snack Sore</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-gradient">
                                            <i class="bi bi-cash-stack me-1"></i>
                                            Rp {{ number_format($booking->nominal_konsumsi, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status == 'approved' ? 'success' : ($booking->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->role !== 'pegawai')
                                    <td>
                                        @if((auth()->user()->role === 'superadmin' || (auth()->user()->role === 'admin_unit' && auth()->user()->unit_id == $booking->meetingRoom->unit_id)) && $booking->status == 'pending')
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-calendar-times fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum ada pemesanan ruang meeting</h5>
                        <p class="text-muted">Silahkan membuat pemesanan ruang meeting pertama Anda</p>
                        <a href="{{ route('bookings.create') }}" class="btn btn-gradient-pln mt-3">
                            <i class="fas fa-plus me-2"></i>Pesan Ruangan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection