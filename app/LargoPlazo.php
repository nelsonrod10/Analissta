<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LargoPlazo extends Model
{
    protected $guarded=[];
    
    public function peligro(){
        return $this->belongsTo('App\Peligro');
    }
}
