<?php

namespace Signalmetrics\Signal\Commands;

use Illuminate\Console\Command;
use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class MoveDataToEventsCommand extends Command {

    public $signature = 'signal:move-data-to-events';

    public $description = 'Move events from Today to Events';

    /**
     * We periodically move data over to the events table since creating indexes is an intensive activity.
     * Today has no indexes, so it writes fast.
     */
    public function handle(): int
    {
        // Disable events
        SignalEvent::withoutEvents(function () {

            // Move records to events table
            SignalToday::chunk(100, function ($todayRecords) {
                // Collect all records in memory first
                $data = [];

                foreach ($todayRecords as $record) {
                    // Store each record's attributes in the array
                    $data[] = $record->getAttributes();
                }

                // Perform a bulk insert into the SignalEvent model
                SignalEvent::insert($data);
            });

        });

        // Optionally, delete records from the 'today' table after moving them
        SignalToday::truncate();

        return self::SUCCESS;
    }


}
