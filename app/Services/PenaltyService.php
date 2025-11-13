<?php

namespace App\Services;

use App\Models\BookLoan;
use App\Models\Penalty;
use App\Models\User;
use Carbon\Carbon;

class PenaltyService
{
    protected $penaltyPerDay;

    public function __construct()
    {
        $this->penaltyPerDay = config('app.penalty_per_day', env('PENALTY_PER_DAY', 50));
    }

    /**
     * Apply late penalty for overdue loans
     */
    public function applyLatePenalty(BookLoan $loan): ?Penalty
    {
        if (!$loan->isOverdue() || $loan->returned_at) {
            return null;
        }

        $daysOverdue = Carbon::parse($loan->due_date)->diffInDays(now());
        if ($daysOverdue <= 0) {
            return null;
        }

        // Check if penalty already exists for this period
        $existingPenalty = Penalty::where('loan_id', $loan->id)
            ->where('type', 'late')
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($existingPenalty) {
            return $existingPenalty;
        }

        $amount = $daysOverdue * $this->penaltyPerDay;

        return Penalty::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'type' => 'late',
            'amount' => $amount,
            'reason' => "Late return penalty for {$daysOverdue} day(s) overdue",
            'is_paid' => false,
        ]);
    }

    /**
     * Apply damage penalty
     */
    public function applyDamagePenalty(BookLoan $loan, float $amount, string $reason): Penalty
    {
        return Penalty::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'type' => 'damage',
            'amount' => $amount,
            'reason' => $reason,
            'is_paid' => false,
        ]);
    }

    /**
     * Apply lost book penalty and suspend user
     */
    public function applyLostBookPenalty(BookLoan $loan, float $replacementFee): Penalty
    {
        $penalty = Penalty::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'type' => 'lost',
            'amount' => $replacementFee,
            'reason' => 'Book was never returned - replacement fee',
            'is_paid' => false,
        ]);

        // Suspend user account
        $user = User::find($loan->user_id);
        if ($user) {
            $user->update(['is_suspended' => true]);
        }

        // Mark loan as lost
        $loan->update(['status' => 'lost']);

        return $penalty;
    }

    /**
     * Check and apply penalties for all overdue loans
     */
    public function checkAndApplyPenalties(): int
    {
        $overdueLoans = BookLoan::where('status', 'active')
            ->where('due_date', '<', now())
            ->whereNull('returned_at')
            ->get();

        $appliedCount = 0;
        foreach ($overdueLoans as $loan) {
            $daysOverdue = Carbon::parse($loan->due_date)->diffInDays(now());

            // If more than 14 days overdue, mark as lost
            if ($daysOverdue > 14) {
                $replacementFee = $this->calculateReplacementFee($loan);
                $this->applyLostBookPenalty($loan, $replacementFee);
                $appliedCount++;
            } else {
                // Apply daily late penalty
                $penalty = $this->applyLatePenalty($loan);
                if ($penalty) {
                    $appliedCount++;
                }

                // Update loan status to overdue
                if ($loan->status !== 'overdue') {
                    $loan->update(['status' => 'overdue']);
                }
            }
        }

        return $appliedCount;
    }

    /**
     * Calculate replacement fee for lost book
     */
    protected function calculateReplacementFee(BookLoan $loan): float
    {
        // Base replacement fee - can be customized
        // For now, using a fixed amount or based on book value
        return 500.00; // PHP 500 default replacement fee
    }

    /**
     * Get total unpaid penalties for a user
     */
    public function getTotalUnpaidPenalties(int $userId): float
    {
        return Penalty::where('user_id', $userId)
            ->where('is_paid', false)
            ->sum('amount');
    }
}

