<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rkl extends Model
{
    protected $table= 'rkls';

    protected $guard= [];

    protected $fillable = [
        'name', // <-- TAMBAHKAN BARIS INI
        'code',];
}
