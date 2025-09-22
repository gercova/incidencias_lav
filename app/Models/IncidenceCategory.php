<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidenceCategory extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "incidence_category";
    protected $guarded = [];
    public $timestamps = false;
}
