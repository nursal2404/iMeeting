<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'tanggal_rapat',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_peserta',
        'snack_siang',
        'makan_siang',
        'snack_sore',
        'nominal_konsumsi',
        'status'
    ];

    protected $casts = [
        'tanggal_rapat' => 'date',
        'snack_siang' => 'boolean',
        'makan_siang' => 'boolean',
        'snack_sore' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    // Method untuk menghitung konsumsi otomatis
    public function calculateConsumption()
    {
        $snackPrice = 20000;
        $lunchPrice = 30000;
        
        $startTime = strtotime($this->waktu_mulai);
        $endTime = strtotime($this->waktu_selesai);
        
        $this->snack_siang = false;
        $this->makan_siang = false;
        $this->snack_sore = false;
        
        // Meeting mulai sebelum jam 11:00
        if ($startTime < strtotime('11:00:00')) {
            $this->snack_siang = true;
        }
        
        // Meeting antara jam 11:00-14:00
        if ($startTime <= strtotime('14:00:00') && $endTime >= strtotime('11:00:00')) {
            $this->makan_siang = true;
        }
        
        // Meeting di atas jam 14:00
        if ($endTime > strtotime('14:00:00')) {
            $this->snack_sore = true;
        }
        
        // Hitung nominal
        $total = 0;
        if ($this->snack_siang) $total += $snackPrice;
        if ($this->makan_siang) $total += $lunchPrice;
        if ($this->snack_sore) $total += $snackPrice;
        
        $this->nominal_konsumsi = $total * $this->jumlah_peserta;
    }
}