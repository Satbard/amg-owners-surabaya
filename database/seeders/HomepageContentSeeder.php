<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageContent;

class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        HomepageContent::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'AMG Owners Surabaya',

                'description' =>
                    'Komunitas pemilik Mercedes-AMG Surabaya.',

                'button_text' => 'Daftar Sekarang',

                'updated_by' => 1,
            ]
        );
    }
}