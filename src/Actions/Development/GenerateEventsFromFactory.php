<?php

namespace Signalmetrics\Signal\Actions\Development;

use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class GenerateEventsFromFactory {

    public int $batch_size = 1000;

    public function handle(int $event_count, $destination = 'today'): void
    {
        $model = ($destination == 'today') ? SignalToday::class : SignalEvent::class;

        $model::withoutEvents(function () use ($event_count, $model) {

            // Calculate the total number of batches
            $total_batches = ceil($event_count / $this->batch_size);

            for ($i = 0; $i < $total_batches; $i ++) {
                // Generate data in memory
                $batch_data = $model::factory()->count($this->batch_size)->make()->toArray();

                // Merge the generated data with the existing data array
                $model::insert($batch_data);
            }

        });


    }

}