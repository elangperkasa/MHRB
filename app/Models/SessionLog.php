<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionLog extends Model
{
    public $timestamps = false; // pakai ini kalau ingin tidak pakai timestampt di data table model
    use HasFactory;
}
