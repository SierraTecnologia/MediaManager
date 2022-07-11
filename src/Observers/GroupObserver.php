<?php

namespace MediaManager\Observers;

use MediaManager\Models\Group;
use MediaManager\Models\Collaborator;
use MediaManager\Models\Phone;
use MediaManager\Models\Addresse;
use MediaManager\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use MediaManager\Util\Filter;
use MediaManager\Services\Operadora;
use MediaManager\Services\FraudAnalysi;

class GroupObserver implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the group "creating" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function creating(Group $group)
    {
        
        return true;
    }

    /**
     * Handle the group "created" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function created(Group $group)
    {
        return $this->change($group);
    }

    /**
     * Handle the group "change" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function change(Group $group)
    {
        
    }

    /**
     * Handle the group "updated" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function updated(Group $group)
    {
        return $this->change($group);
    }

    /**
     * Handle the group "deleted" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function deleted(Group $group)
    {
        //
    }

    /**
     * Handle the group "restored" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function restored(Group $group)
    {
        //
    }

    /**
     * Handle the group "force deleted" event.
     *
     * @param  \MediaManager\Models\Group  $group
     * @return void
     */
    public function forceDeleted(Group $group)
    {
        //
    }
}
