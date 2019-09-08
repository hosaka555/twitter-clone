<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Relationship extends Pivot
{
    protected $table = 'relation_ships';
    public $timestamps = false;
    protected $guard = [];
}
