<?php

namespace MediaManager\Observers;

use MediaManager\Models\Computer;
use MediaManager\Services\FraudAnalysi;
use MediaManager\Services\Operadora;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ComputerObserver implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the computer "creating" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function creating(Computer $computer)
    {
        if ($computer->is_active=='') {
            $computer->is_active = 1;
        }

        // @todo Essa funcao nao existe deveria dar getError
        // Esse observer provavemente nao ta ativado
        // $computer->generateTokenIfNull();
        return true;
    }

    /**
     * Handle the computer "created" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function created(Computer $computer)
    {
        return true;
    }

    /**
     * Handle the computer "updated" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function updated(Computer $computer)
    {
        return true;
    }

    /**
     * Handle the computer "deleted" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function deleted(Computer $computer)
    {
        //
    }

    /**
     * Handle the computer "restored" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function restored(Computer $computer)
    {
        //
    }

    /**
     * Handle the computer "force deleted" event.
     *
     * @param  \MediaManager\Models\Computer  $computer
     * @return void
     */
    public function forceDeleted(Computer $computer)
    {
        //
    }
}
