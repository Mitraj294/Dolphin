<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'status',
    ];

    protected $casts = [
        'assessment_sent_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    /**
     * Get all notes for the lead.
     */
    public function notes()
    {
        return $this->hasMany(LeadNote::class)->orderBy('note_date', 'desc');
    }

    /**
     * Get the sales person assigned to this lead.
     */
    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    /**
     * Get the user who created this lead.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the referral source for this lead.
     */
    public function referralSource()
    {
        return $this->belongsTo(ReferralSource::class);
    }

    /**
     * Get the country for this lead.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state for this lead.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city for this lead.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the organization associated with the lead.
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id');
    }
}
