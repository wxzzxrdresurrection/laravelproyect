<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
    use HasFactory;
    protected $table = "casas";
    protected $fillable = [
        'nombre'
    ];

    public function users(){

        return $this->belongsTo(User::class);
    }
}
