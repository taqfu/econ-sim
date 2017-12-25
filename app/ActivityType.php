<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    const WANDER = 2;
    const SLEEP = 3;
    const HEAD_TO_BED = 4;
    const HEAD_TO_WORK = 5;
    const UNLOAD_SHIP = 6;
    const PAPERWORK = 7;
}
