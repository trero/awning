<?php

namespace Trero\Awning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwningChecksum extends Model
{
    use HasFactory;

    protected $table = 'awning_checksum';

    protected $fillable = [
        'checksum_array'
    ];
}
