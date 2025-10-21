<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;
use App\Models\MeetingRoom;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Units
        $unit1 = Unit::create([
            'kode_unit' => 'UIDJAYA',
            'nama_unit' => 'Unit Induk Jaya'
        ]);

        $unit2 = Unit::create([
            'kode_unit' => 'UIDSBY',
            'nama_unit' => 'Unit Induk Surabaya'
        ]);

        // Create Meeting Rooms
        MeetingRoom::create([
            'unit_id' => $unit1->id,
            'nama_ruang' => 'Ruang Prambanan',
            'kapasitas' => 20
        ]);

        MeetingRoom::create([
            'unit_id' => $unit1->id,
            'nama_ruang' => 'Ruang Borobudur',
            'kapasitas' => 15
        ]);

        MeetingRoom::create([
            'unit_id' => $unit2->id,
            'nama_ruang' => 'Ruang Majapahit',
            'kapasitas' => 25
        ]);

        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@pln.co.id',
            'password' => bcrypt('password'),
            'role' => 'superadmin'
        ]);

        // Create Admin Unit
        User::create([
            'name' => 'Admin UID Jaya',
            'email' => 'admin.jaya@pln.co.id',
            'password' => bcrypt('password'),
            'unit_id' => $unit1->id,
            'role' => 'admin_unit'
        ]);

        // Create Pegawai
        User::create([
            'name' => 'Pegawai Contoh',
            'email' => 'pegawai@pln.co.id',
            'password' => bcrypt('password'),
            'unit_id' => $unit1->id,
            'role' => 'pegawai'
        ]);
    }
}