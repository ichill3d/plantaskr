<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReactionsTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('reactions_types')->insert([
            [
                'name' => 'Like',
                'shortcode' => '👍',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
