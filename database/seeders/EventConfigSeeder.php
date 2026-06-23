<?php

namespace Database\Seeders;

use App\Models\EventConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        EventConfig::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        EventConfig::create([
            'event_name'          => 'SRI 2026',
            'event_slug'          => 'sri-2026',
            'event_description'   => 'Semaine de la Recherche et de l\'Innovation de l\'Université Cheikh Anta Diop de Dakar',
            'organizer'           => 'Direction de la Recherche et de l\'Innovation – UCAD',
            'event_start_date'    => '2026-12-01',
            'event_end_date'      => '2026-12-05',
            'submission_open_at'  => '2026-09-01 08:00:00',
            'submission_close_at' => '2026-11-01 23:59:59',
            'is_active'           => true,
        ]);
    }
}
