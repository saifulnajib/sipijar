<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idpel extends Model
{
    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function substations()
    {
        return $this->hasMany(Substation::class);
    }
}
