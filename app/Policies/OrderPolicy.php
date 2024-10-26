<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // TODO : We can add a condition to check if the user is admin so allow him to do this action

    /**
     * Determine if the authenticated user can view the order.
     */
    public function view(User $user, Order $order)
    {
        // Only allow users to view their own orders
        return $user->id === $order->user_id;
    }

    /**
     * Determine if the authenticated user can create an order.
     */
    public function create(User $user)
    {
        // For this example, allow all authenticated users to create orders
        return true;
    }

    /**
     * Determine if the authenticated user can update the order.
     */
    public function update(User $user, Order $order)
    {
        // Only allow users to update their own orders
        return $user->id === $order->user_id;
    }

    /**
     * Determine if the authenticated user can delete the order.
     */
    public function delete(User $user, Order $order)
    {
        // Only allow users to delete their own orders
        return $user->id === $order->user_id;
    }
}

