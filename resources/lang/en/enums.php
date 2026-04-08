<?php

declare(strict_types=1);

return [
    'credit_note_status' => [
        'draft' => 'Draft',
        'finalized' => 'Finalized',
        'voided' => 'Voided',
    ],
    'document_render_type' => [
        'html' => 'HTML',
        'pdf' => 'PDF',
        'xml' => 'XML',
        'factur_x' => 'Factur-X',
    ],
    'e_invoice_format' => [
        'factur_x' => 'Factur-X',
        'ubl' => 'UBL',
        'cii' => 'CII',
    ],
    'invoice_status' => [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'finalized' => 'Finalized',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
        'overdue' => 'Overdue',
    ],
    'invoice_type' => [
        'invoice' => 'Invoice',
        'credit_note' => 'Credit note',
        'quote' => 'Quote',
    ],
    'payment_method_type' => [
        'bank_transfer' => 'Bank transfer',
        'direct_debit' => 'Direct debit',
        'card' => 'Card',
        'cash' => 'Cash',
        'cheque' => 'Cheque',
        'other' => 'Other',
    ],
    'payment_status' => [
        'pending' => 'Pending',
        'partially_paid' => 'Partially paid',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ],
    'quote_status' => [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'expired' => 'Expired',
    ],
    'reminder_channel' => [
        'email' => 'Email',
        'sms' => 'SMS',
        'letter' => 'Letter',
        'internal' => 'Internal',
    ],
    'sequence_reset_policy' => [
        'never' => 'Never',
        'monthly' => 'Monthly',
        'annual' => 'Annual',
    ],
    'tax_applicability_type' => [
        'standard' => 'Standard',
        'exempt' => 'Exempt',
        'reverse_charge' => 'Reverse charge',
        'out_of_scope' => 'Out of scope',
    ],
];
