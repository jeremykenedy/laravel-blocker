<?php

namespace jeremykenedy\LaravelBlocker\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;

class DefaultBlockedTypeTableSeeder extends Seeder
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
        foreach ($BlockedTypes as $BlockedType) {
            $newBlockedType = BlockedType::where('slug', '=', $BlockedType['slug'])->first();
            if ($newBlockedType === null) {
                $newBlockedType = BlockedType::create([
                    'slug' => $BlockedType['slug'],
                    'name' => $BlockedType['name'],
                ]);
            }
        }
        echo "\e[32mSeeding:\e[0m DefaultBlockedTypeTableSeeder\r\n";
    }
}
