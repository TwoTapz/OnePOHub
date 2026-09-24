<?php

namespace App\Support;

class PaymentStatus
{
    public static function for(array $po): array
    {
        $total = (float) ($po['total'] ?? 0);
        $paid = (float) ($po['amount_paid'] ?? 0);
        $term = $po['payment_term'] ?? 'full_on_completion';

        if ($paid >= $total && $total > 0) {
            return ['label' => 'Fully Paid', 'color' => 'green'];
        }

        if ($term === 'deposit') {
            if ($paid === 0.0) {
                return ['label' => 'Awaiting Deposit', 'color' => 'yellow'];
            }
            return ['label' => 'Deposit Paid RM ' . number_format($paid, 2), 'color' => 'blue'];
        }

        return ['label' => 'Pending Payment', 'color' => 'orange'];
    }
}
