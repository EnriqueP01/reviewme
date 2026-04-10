<?php

namespace App\Listeners;

use Illuminate\Database\Events\Models\ModelCreated;

class UpdateUserKarma
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ModelCreated $event): void
    {
        //
    }
}
