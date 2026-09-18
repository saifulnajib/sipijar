<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\District;
use App\Models\Idpel;
use App\Models\Substation;
use App\Models\PjuPole;
use App\Models\Lamp;
use App\Models\BillingHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin SIPIJAR',
            'email' => 'admin@sipijar.id',
            'password' => bcrypt('password'),
        ]);

        // Districts
        $districts = [
            ['name' => 'Bukit Bestari', 'slug' => 'bukit-bestari'],
            ['name' => 'Tanjungpinang Timur', 'slug' => 'tanjungpinang-timur'],
            ['name' => 'Tanjungpinang Kota', 'slug' => 'tanjungpinang-kota'],
            ['name' => 'Tanjungpinang Barat', 'slug' => 'tanjungpinang-barat'],
        ];

        $districtModels = [];
        foreach ($districts as $d) {
            $districtModels[] = District::create($d);
        }

        // Data targets
        $totalIdpel = 1482;
        $totalMeterisasi = 1156;
        $totalLamps = 8940;
        
        $smartLedCount = round($totalLamps * 0.685);
        $ledKonvensionalCount = round($totalLamps * 0.218);
        $sonTCount = $totalLamps - $smartLedCount - $ledKonvensionalCount;

        // Let's create Idpels and Substations using bulk inserts for speed
        $idpelData = [];
        for ($i = 0; $i < $totalIdpel; $i++) {
            $status = ($i < $totalMeterisasi) ? 'meterisasi' : 'abonemen';
            $districtId = $districtModels[array_rand($districtModels)]->id;
            
            $lat = 0.9167 + (mt_rand(-500, 500) / 10000);
            $lng = 104.4500 + (mt_rand(-500, 500) / 10000);

            $idpelData[] = [
                'district_id' => $districtId,
                'idpel_number' => '14' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'name' => 'Panel PJU ' . ($i + 1),
                'status' => $status,
                'address' => 'Jalan ' . Str::random(10),
                'lat' => $lat,
                'lng' => $lng,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Chunk inserts for SQLite limitations
        foreach (array_chunk($idpelData, 500) as $chunk) {
            Idpel::insert($chunk);
        }
        
        $insertedIdpels = Idpel::pluck('id')->toArray();
        
        $substationData = [];
        foreach ($insertedIdpels as $idpelId) {
            $substationData[] = [
                'idpel_id' => $idpelId,
                'name' => 'Gardu IDPEL ' . $idpelId,
                'current_kw' => mt_rand(10, 50) / 10,
                'current_voltage' => 220,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($substationData, 500) as $chunk) {
            Substation::insert($chunk);
        }
        $insertedSubstations = Substation::pluck('id')->toArray();
        
        $lampTypes = array_merge(
            array_fill(0, $smartLedCount, 'Smart LED'),
            array_fill(0, $ledKonvensionalCount, 'LED Konvensional'),
            array_fill(0, $sonTCount, 'Son-T')
        );
        shuffle($lampTypes);

        $poleData = [];
        $lampData = [];
        
        for ($i = 0; $i < $totalLamps; $i++) {
            $substationId = $insertedSubstations[$i % count($insertedSubstations)];
            
            $poleData[] = [
                'substation_id' => $substationId,
                'pole_number' => 'PJU-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'lat' => 0.9167 + (mt_rand(-500, 500) / 10000),
                'lng' => 104.4500 + (mt_rand(-500, 500) / 10000),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($poleData, 500) as $chunk) {
            PjuPole::insert($chunk);
        }
        $insertedPoles = PjuPole::pluck('id')->toArray();
        
        for ($i = 0; $i < $totalLamps; $i++) {
            $type = $lampTypes[$i];
            $watt = $type === 'Son-T' ? 250 : ($type === 'Smart LED' ? 60 : 90);
            
            $lampData[] = [
                'pju_pole_id' => $insertedPoles[$i],
                'type' => $type,
                'power_watt' => $watt,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($lampData, 500) as $chunk) {
            Lamp::insert($chunk);
        }
        
        $billingData = [];
        for ($month = 1; $month <= 12; $month++) {
            $isAfter = $month > 6;
            
            foreach ($insertedIdpels as $idpelId) {
                $baseBilled = $isAfter ? 618000 : 1059000;
                $billed = $baseBilled + mt_rand(-50000, 50000);
                
                $billingData[] = [
                    'idpel_id' => $idpelId,
                    'month' => $month,
                    'year' => date('Y'),
                    'amount_billed' => $billed,
                    'kwh_usage' => $billed / 1500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        foreach (array_chunk($billingData, 500) as $chunk) {
            BillingHistory::insert($chunk);
        }
    }
}
