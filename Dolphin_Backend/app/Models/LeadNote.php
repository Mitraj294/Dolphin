<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'note',
        'note_date',
        'created_by',
    ];

    protected $casts = [
        'note_date' => 'datetime',
    ];

    /**
     * Get the lead that owns the note.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who created the note.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
