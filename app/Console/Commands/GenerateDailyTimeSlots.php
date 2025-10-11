<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeSlot;
use Carbon\Carbon;

class GenerateDailyTimeSlots extends Command
{
    protected $signature = 'generate:daily-time-slots';

    protected $description = 'Generate daily time slots for mechanics';

    public function handle()
    {
        $mechanics = User::where('role', 'mekanik')->get();
        $startHour = 9;
        $endHour = 17;
        $date = Carbon::today();

        foreach ($mechanics as $mechanic) {
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                TimeSlot::firstOrCreate([
                    'mechanic_id' => $mechanic->id,
                    'date' => $date->toDateString(),
                    'start_time' => Carbon::createFromTime($hour, 0, 0)->toTimeString(),
                ], [
                    'end_time' => Carbon::createFromTime($hour + 1, 0, 0)->toTimeString(),
                    'is_available' => true,
                ]);
            }
        }

        $this->info('Daily time slots generated successfully.');
    }
}
