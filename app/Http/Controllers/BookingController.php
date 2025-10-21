<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['meetingRoom.unit', 'user'])
            ->when(Auth::user()->role === 'admin_unit', function($query) {
                return $query->whereHas('meetingRoom.unit', function($q) {
                    $q->where('id', Auth::user()->unit_id);
                });
            })
            ->when(Auth::user()->role === 'pegawai', function($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->orderBy('tanggal_rapat', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $units = Unit::all();
        $meetingRooms = MeetingRoom::with('unit')->get();
        
        return view('bookings.create', compact('units', 'meetingRooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'tanggal_rapat' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_peserta' => 'required|integer|min:1'
        ]);

        // Validasi kapasitas ruangan
        $meetingRoom = MeetingRoom::find($validated['meeting_room_id']);
        if ($validated['jumlah_peserta'] > $meetingRoom->kapasitas) {
            return back()->withErrors([
                'jumlah_peserta' => 'Jumlah peserta melebihi kapasitas ruangan (Max: ' . $meetingRoom->kapasitas . ')'
            ])->withInput();
        }

        // Validasi waktu tidak overlap
        $existingBooking = Booking::where('meeting_room_id', $validated['meeting_room_id'])
            ->where('tanggal_rapat', $validated['tanggal_rapat'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('waktu_mulai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                      ->orWhereBetween('waktu_selesai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('waktu_mulai', '<=', $validated['waktu_mulai'])
                            ->where('waktu_selesai', '>=', $validated['waktu_selesai']);
                      });
            })
            ->where('status', 'approved')
            ->first();

        if ($existingBooking) {
            return back()->withErrors([
                'waktu_mulai' => 'Ruangan sudah dipesan pada jam tersebut'
            ])->withInput();
        }

        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->meeting_room_id = $validated['meeting_room_id'];
        $booking->tanggal_rapat = $validated['tanggal_rapat'];
        $booking->waktu_mulai = $validated['waktu_mulai'];
        $booking->waktu_selesai = $validated['waktu_selesai'];
        $booking->jumlah_peserta = $validated['jumlah_peserta'];
        
        // Hitung konsumsi otomatis
        $booking->calculateConsumption();
        
        if ($booking->save()) {
            return redirect()->route('bookings.index')->with('success', 'Pemesanan berhasil diajukan');
        }

        return back()->with('error', 'Gagal membuat pemesanan');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        // Authorization check
        if (!Auth::user()->role === 'superadmin' && 
            !(Auth::user()->role === 'admin_unit' && Auth::user()->unit_id == $booking->meetingRoom->unit_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', 'Status pemesanan berhasil diupdate');
    }
}