<?php
namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine if the user can view a specific transaction.
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id || $user->hasRole('admin');
    }

    /**
     * Determine if the user can view all transactions.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can process a payment.
     */
    public function processPayment(User $user)
    {
        return $user->hasRole('admin');
    }
}

