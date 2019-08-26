<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'replies';

    public $primaryKey = 'id';

    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }
}
