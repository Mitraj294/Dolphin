<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organization_addresses';

    protected $fillable = [
        'organization_id',
        'address_line_1',
        'address_line_2',
        'city_id',
        'state_id',
        'country_id',
        'zip_code',
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
