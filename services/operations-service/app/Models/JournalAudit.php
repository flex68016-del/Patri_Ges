<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'acteur_id',
        'action',
        'objet_type',
        'objet_id',
        'detail',
    ];

    protected $casts = [
        'detail' => 'array',
    ];
}
