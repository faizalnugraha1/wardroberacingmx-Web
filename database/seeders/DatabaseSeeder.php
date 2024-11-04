<?php

namespace Database\Seeders;

use App\Models\Alamat;
use App\Models\Flag;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin','guard_name' => 'web']);
        Role::create(['name' => 'user','guard_name' => 'web']);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('wrmx123'),
            'is_active' => true,
        ])->assignRole('admin');

        User::create([
            'name' => 'Admin WRDBMX',
            'email' => 'wrdbmx@gmail.com',
            'password' => bcrypt('wrmx123'),
            'is_active' => true,
        ])->assignRole('admin');

        User::create([
            'name' => 'Fadilah Muh',
            'email' => 'fadilahmuhammad800@gmail.com',
            'password' => bcrypt('12345678'),
            'is_active' => true,
        ])->assignRole('user');
       
        Flag::create(['type' => 'booking','status' => 'Menunggu', 'keterangan' => 'Menunggu persetujuan Admin']);
        Flag::create(['type' => 'booking','status' => 'Dibatalkan', 'keterangan' => 'Dibatalkan oleh Admin']);
        Flag::create(['type' => 'booking','status' => 'Diterima', 'keterangan' => 'Jadwal diterima oleh Admin']);
        Flag::create(['type' => 'booking','status' => 'Pengerjaan', 'keterangan' => 'Sedang dalam pengerjaan']);
        Flag::create(['type' => 'booking','status' => 'Selesai', 'keterangan' => 'Proses pengerjaan bengkel selesai']);
        Flag::create(['type' => 'booking','status' => 'Dibatalkan', 'keterangan' => 'Booking dibatalkan oleh Sistem']);
        Flag::create(['type' => 'pesanan','status' => 'Menunggu', 'keterangan' => 'Menunggu pembayaran']);
        Flag::create(['type' => 'pesanan','status' => 'Dibatalkan', 'keterangan' => 'Dibatalkan oleh Sistem']);
        Flag::create(['type' => 'pesanan','status' => 'Dibatalkan', 'keterangan' => 'Dibatalkan oleh User']);
        Flag::create(['type' => 'pesanan','status' => 'Menunggu', 'keterangan' => 'Menunggu pemeriksaan admin']);
        Flag::create(['type' => 'pesanan','status' => 'Dibatalkan', 'keterangan' => 'Dibatalkan oleh Admin']);
        Flag::create(['type' => 'pesanan','status' => 'Diproses', 'keterangan' => 'Pesanan sedang dalam proses']);
        Flag::create(['type' => 'pesanan','status' => 'Diproses', 'keterangan' => 'Menunggu proses dari kurir ekspedisi']);
        Flag::create(['type' => 'pesanan','status' => 'Dalam Perjalanan', 'keterangan' => 'Pesanan sedang dalam perjalanan']);
        Flag::create(['type' => 'pesanan','status' => 'Selesai', 'keterangan' => 'Pesanan telah diterima']);
        Flag::create(['type' => 'pesanan','status' => 'Selesai', 'keterangan' => 'Pesanan diselesaikan oleh sistem']);

        $row = 0;
        $file_handle = fopen(public_path('rajaongkir_provinsi.csv'), 'r');
        while (($data = fgetcsv($file_handle, 0, ",")) !== FALSE) {
            $row++;
            if ($row > 1)
            {
                Provinsi::create([
                    'id' => $data[0],
                    'nama' => $data[1],
                ]);
            }
            
        }
        fclose($file_handle);

        $row = 0;
        $file_handle = fopen(public_path('rajaongkir_kota.csv'), 'r');
        while (($data = fgetcsv($file_handle, 0, ",")) !== FALSE) {
            $row++;
            if ($row > 1)
            {
                Kota::create([
                    'id' => $data[0],
                    'provinsi_id' => $data[1],
                    'nama' => $data[2],
                    'kode_pos' => $data[3],
                ]);
            }
            
        }
        fclose($file_handle);

        $row = 0;
        $file_handle = fopen(public_path('rajaongkir_kecamatan.csv'), 'r');
        while (($data = fgetcsv($file_handle, 0, ",")) !== FALSE) {
            $row++;
            if ($row > 1)
            {
                Kecamatan::create([
                    'id' => $data[0],
                    'kota_id' => $data[1],
                    'nama' => $data[2],
                ]);
            }
            
        }
        fclose($file_handle);

        Alamat::create([
            'user_id' => 3,
            'detail' => 'Sukasenang',
            'kode_pos' => '40124',
            'provinsi_id' => 9,
            'kota_id' => 23,
            'kecamatan_id' => 351,
            'kelurahan' => 'Cikutra',
        ]);
    }
}
