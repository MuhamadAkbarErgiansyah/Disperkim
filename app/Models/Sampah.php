<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $jenis_sampah
 * @property int $harga
 * @property string $satuan
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Sampah extends Model
{
    use HasFactory;

    protected $table = 'sampah';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'jenis_sampah',
        'harga',
        'satuan',
        'deskripsi'
    ];

    protected $casts = [
        'harga' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
