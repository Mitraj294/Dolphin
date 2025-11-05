<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'zip',
    ];

    /**
     * Get the organization that owns the address.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
