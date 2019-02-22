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
         * Blocked Types
         *
         */
        /*
         * Blocked Types
         *
         */
        $BlockedTypes = [
            [
                'slug' => 'email',
                'name' => 'E-mail',
            ],
            [
                'slug' => 'ipAddress',
                'name' => 'IP Address',
            ],
            [
                'slug' => 'domain',
                'name' => 'Domain Name',
            ],
        ];

        /*
         * Add Blocked Types
         *
         */
        if (config('laravelblocker.seedPublishedBlockedTypes')) {
            foreach ($BlockedTypes as $BlockedType) {
                $newBlockedType = BlockedType::where('slug', '=', $BlockedType['slug'])->first();
                if ($newBlockedType === null) {
                    $newBlockedType = BlockedType::create([
                        'slug' => $BlockedType['slug'],
                        'name' => $BlockedType['name'],
                    ]);
                }
            }
        }
    }
}
