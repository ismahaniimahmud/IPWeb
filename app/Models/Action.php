<?php

namespace App\Models;

use Database\Factories\ActionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /** @use HasFactory<ActionFactory> */
    use HasFactory;

    protected $fillable = [
        'action_code', 'reference', 'theme', 'required_action', 'owner', 'target', 'due_date',
        'status', 'priority', 'basis', 'progress', 'evidence', 'updated_on',
        'source_note', 'meeting_date',
    ];

    protected function casts(): array
    {
        return ['updated_on' => 'date', 'meeting_date' => 'date', 'due_date' => 'date'];
    }
}
