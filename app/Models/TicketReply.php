<?php

namespace App\Models;

use App\Enums\ReplyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'body',
        'reply_type',
    ];

    /**
     * The ticket this reply belongs to.
     */


     protected $casts = [
        'reply_type' => ReplyType::class,
     ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The user who wrote this reply.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Determine if the reply is internal.
     *
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->reply_type === 'internal';
    }

    /**
     * Determine if the reply is external.
     *
     * @return bool
     */
    public function isExternal(): bool
    {
        return $this->reply_type === 'external';
    }
}
