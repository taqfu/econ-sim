<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    const GOLD = 6;
    const SILVER = 7;
    const WAGES = [ItemType::GOLD, ItemType::SILVER];
}
