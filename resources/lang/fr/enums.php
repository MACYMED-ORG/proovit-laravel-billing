<?php

declare(strict_types=1);

return [
    'credit_note_status' => [
        'draft' => 'Brouillon',
        'finalized' => 'Finalisé',
        'voided' => 'Annulé',
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
        'draft' => 'Brouillon',
        'pending' => 'En attente',
        'finalized' => 'Finalisée',
        'paid' => 'Payée',
        'cancelled' => 'Annulée',
        'overdue' => 'En retard',
    ],
    'invoice_type' => [
        'invoice' => 'Facture',
        'credit_note' => 'Avoir',
        'quote' => 'Devis',
    ],
    'payment_method_type' => [
        'bank_transfer' => 'Virement bancaire',
        'direct_debit' => 'Prélèvement',
        'card' => 'Carte',
        'cash' => 'Espèces',
        'cheque' => 'Chèque',
        'other' => 'Autre',
    ],
    'payment_status' => [
        'pending' => 'En attente',
        'partially_paid' => 'Partiellement payée',
        'paid' => 'Payée',
        'failed' => 'Échouée',
        'refunded' => 'Remboursée',
    ],
    'quote_status' => [
        'draft' => 'Brouillon',
        'sent' => 'Envoyé',
        'accepted' => 'Accepté',
        'rejected' => 'Refusé',
        'expired' => 'Expiré',
    ],
    'reminder_channel' => [
        'email' => 'Email',
        'sms' => 'SMS',
        'letter' => 'Courrier',
        'internal' => 'Interne',
    ],
    'sequence_reset_policy' => [
        'never' => 'Jamais',
        'monthly' => 'Mensuel',
        'annual' => 'Annuel',
    ],
    'tax_applicability_type' => [
        'standard' => 'Standard',
        'exempt' => 'Exonéré',
        'reverse_charge' => 'Autoliquidation',
        'out_of_scope' => 'Hors champ',
    ],
];
