<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;

class BlockedTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Blocked Types
         *
         */
        if (BlockedType::where('type', '=', 'email')->first() === null) {
            $adminBlockedType = BlockedType::create([
                'type'        => 'email',
            ]);
        }

        if (BlockedType::where('type', '=', 'ipAddress')->first() === null) {
            $adminBlockedType = BlockedType::create([
                'type'        => 'ipAddress',
            ]);
        }
    }
}
