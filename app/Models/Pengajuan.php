<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // include user_id in fillable
    protected $fillable = [
        'asset_id',
        'user_id',
        'nama_pengaju',
        'catatan',
        'foto',
        'status'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
