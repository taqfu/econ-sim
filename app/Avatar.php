<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
	const AVG_CAL_REQ = 2500;
	const AVG_SLEEP_REQ = 8;
	const AVG_DAYS_TO_STARVE=24;
	const SEX = ["invalid", "XY", "XX"];
}
