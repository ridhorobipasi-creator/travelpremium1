<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered in the controller's query
    }

    /**
     * Priority 4: Granular Data Protection Logic
     * Determine whether the user can view the specific booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->role === 'admin' || 
               $user->role === 'staff' || 
               $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only Staff/Admin can update, but we keep policy for consistency
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    /**
     * Only Admin can delete
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->role === 'admin';
    }
}
