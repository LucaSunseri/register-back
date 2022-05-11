<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities = [
            'Attività 1',
            'Attività 2',
            'Attività 3',
        ];

        foreach ($activities as $activity) {

            $new_activity = new Activity();

            $new_activity->type = $activity;

            $new_activity->save();
        }
    }
}
