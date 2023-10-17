<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTicketStatus
 */
class TicketStatus extends Model
{
    use HasFactory;

    // Ticket status constants
    const AVAILABLE = 1; // Ticket is available for purchase.
    const SOLD_OUT = 2; // Ticket is sold out.
    const RESERVED = 3; // Ticket is reserved for a user.
    const PENDING_APPROVAL = 4; // Ticket is pending approval.
    const CANCELLED = 5; // Ticket is cancelled.
}
