<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    protected $fillable = [
        'workflow_id',
        'name',
        'trigger_data'
    ];

    protected $casts = [
        'trigger_data' => 'array'
    ];

    /**
     * Relación con Contacts
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'workflow_id', 'workflow_id');
    }
}
