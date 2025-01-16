<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Department;
use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'body',
        'init_user_id',
        'init_doctor_id',
        'assigned_user_id',
        'department',
        'category',
        'status',
        'attachement',
        'priority',
        'closed_at',
        'closed_by',
        'branch_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'department' => Department::class,
        'category' => Category::class,
        'status' => Status::class,
        'priority' => Priority::class,
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who initiated the ticket.
     */

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function initUser()
    {
        return $this->belongsTo(User::class, 'init_user_id');
    }

    /**
     * Get the doctor who initiated the ticket.
     */
    public function initDoctor()
    {
        return $this->belongsTo(Doctor::class, 'init_doctor_id');
    }

    /**
     * Get the user assigned to the ticket.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Get the user who closed the ticket.
     */
    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Determine if the ticket is complete.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->status === Status::Complete;
    }

    /**
     * Accessor for the 'priority' attribute to capitalize the first letter.
     *
     * @param Priority $value
     * @return string
     */

    /**
     * Mutator to ensure the title is always stored in title case.
     *
     * @param string $value
     * @return void
     */
    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = ucwords($value);
    }

    /**
     * Scope a query to only include tickets of a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Status $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, Status $status)
    {
        return $query->where('status', $status->value);
    }

    /**
     * Scope a query to only include tickets of a given department.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Department $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDepartment($query, Department $department)
    {
        return $query->where('department', $department->value);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
