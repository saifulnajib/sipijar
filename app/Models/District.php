<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function idpels()
    {
        return $this->hasMany(Idpel::class);
    }
}
