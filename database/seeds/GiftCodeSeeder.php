<?php

use App\GCList;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class GiftCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [];
        for ($i = 0; $i < 800000; $i++) {

            do {
                $generated_qr_code = Str::upper(Str::random(7));
                $gc = GCList::where('qr_reference_number', "BDO$generated_qr_code")->exists();
            } while ($gc);

            $records[] = [
                'campaign_id' => 20, //latest campaign
                'name' => "BDO CUSTOMER",
                'qr_reference_number' => "BDO$generated_qr_code",
                'is_fetch' => 0,
                'created_by' => 125 //stella
            ];

            // Insert in batches of 1000 to prevent memory overflow
            if (count($records) == 1000) {
                GCList::insert($records);
                $records = [];
            }
        }

        // Insert remaining records
        if (!empty($records)) {
            GCList::insert($records);
        }
    }
}
