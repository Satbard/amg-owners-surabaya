<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-update event status based on event_date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // upcoming → ongoing: if event_date has passed
        $upcomingCount = Event::where('status', 'upcoming')
            ->where('event_date', '<=', $now)
            ->update(['status' => 'ongoing']);

        // ongoing → completed: if event_date + 1 day has passed
        $ongoingCount = Event::where('status', 'ongoing')
            ->where('event_date', '<=', $now->copy()->subDay())
            ->update(['status' => 'completed']);

        $this->info("Updated {$upcomingCount} events to ongoing, {$ongoingCount} to completed.");
    }
}
