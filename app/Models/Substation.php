<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    protected $guarded = [];

    public function idpel()
    {
        return $this->belongsTo(Idpel::class);
    }

    public function pjuPoles()
    {
        return $this->hasMany(PjuPole::class);
    }
}
