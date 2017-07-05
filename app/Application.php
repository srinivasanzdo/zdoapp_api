<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['application_no',
                           'name',
                           'gender',
                           'nric',
                           'dob',
                           'occupation', 
                           'contact', 
                           'nok_relationship', 
                           'contact_nok', 
                           'shieldplan_paplan_date', 
                           'exclusionplan', 
                           'preexisting_condition',
                           'high_blood_pressure',
                           'diabetes',
                           'high_cholesterol',
                           'diagnosisdate',
                           'pending_claims',
                           'other_entitlement_policies',
                           'cause_complaint',
                           'signs_symptoms',
                           'preffereddate',
                           'status_id',
                           'user_id',
                           'remark',
                           'amend_remark',
                           'reject_remark',
                           'rejected'];

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function scopeInfo($query)
	{
    	return $query->with('status', 'user');
	}

    public function scopeInfoadmin($query)
	{
    	return $query->with('status', 'user')->where('status_id','!=',3)->where('status_id','!=',7);
	}

    public function scopePending($query)
	{
    	return $query->where('status_id', 1);
	}

    public function scopeAmend($query)
	{
    	return $query->where('status_id', 2);
	}

    public function scopeDraft($query)
	{
    	return $query->where('status_id', 3);
	}

    public function scopeApproved($query)
	{
    	return $query->where('status_id', 4);
	}

    public function scopeRejected($query)
	{
    	return $query->where('status_id', 5);
	}

    public function scopeResubmit($query)
	{
    	return $query->where('rejected', "Yes");
	}

    public function scopeReceived($query)
	{
    	return $query->where('status_id', 7);
	}
}
