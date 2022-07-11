<?php

namespace MediaManager\Observers;

use MediaManager\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MediaObserver implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the media "created" event.
     *
     * @param  \MediaManager\Models\Media  $media
     * @return void
     */
    public function created(Media $media)
    {
        return true;
    }

    /**
     * Handle the media "updated" event.
     *
     * @param  \MediaManager\Models\Media  $media
     * @return void
     */
    public function updated(Media $media)
    {
        return true;
    }

    /**
     * Handle the media "deleted" event.
     *
     * @param  \MediaManager\Models\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        //
    }

    /**
     * Handle the media "restored" event.
     *
     * @param  \MediaManager\Models\Media  $media
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the media "force deleted" event.
     *
     * @param  \MediaManager\Models\Media  $media
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
