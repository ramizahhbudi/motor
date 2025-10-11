<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TimeSlot;
use Carbon\Carbon;

class TimeSlotSeeder extends Seeder
{
    public function run()
    {
        $mechanics = User::where('role', 'mekanik')->get();
        $startHour = 9;
        $endHour = 17;
        $date = Carbon::today();

        foreach ($mechanics as $mechanic) {
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                TimeSlot::create([
                    'mechanic_id' => $mechanic->id,
                    'date' => $date->toDateString(),
                    'start_time' => Carbon::createFromTime($hour, 0, 0)->toTimeString(),
                    'end_time' => Carbon::createFromTime($hour + 1, 0, 0)->toTimeString(),
                    'is_available' => true,
                ]);
            }
        }
    }
}
