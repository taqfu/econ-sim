<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    const SLEEP = 3;
    const WANDER = 2;
    const HEAD_TO_BED = 4;
}
