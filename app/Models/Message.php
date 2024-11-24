<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'messages';
    public $timestamps = true;

    public function sender(){
        return $this->belongsTo(User::class);
    }
    public function recipient(){
        return $this->belongsTo(User::class);
    }

}
