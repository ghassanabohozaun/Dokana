<?php

return [
    'payment_entities' => 'Payment Entities',
    'payment_entity' => 'Payment Entity',
    'create_new_payment_entity' => 'Add New Payment Entity',
    'update_payment_entity' => 'Update Payment Entity',
    'show_all_payment_entities' => 'Show All Payment Entities',
    
    // Fields
    'name' => 'Entity Name',
    'name_ar' => 'Entity Name (Arabic)',
    'name_en' => 'Entity Name (English)',
    'type' => 'Type',
    'type_bank' => 'Bank',
    'type_wallet' => 'Wallet',
    'status' => 'Status',
    'status_active' => 'Active',
    'status_inactive' => 'Inactive',
    
    // Placeholders
    'select_type' => 'Select type...',
    'enter_name_ar' => 'Enter name in Arabic...',
    'enter_name_en' => 'Enter name in English...',
    
    // UI Elements
    'no_payment_entities_found' => 'No payment entities found!',
    
    // Validation
    'name_ar_required' => 'Please enter the name in Arabic',
    'name_en_required' => 'Please enter the name in English',
    'type_required' => 'Please select the type',
    'delete_restriction' => 'Sorry, this payment entity cannot be deleted because it has linked bank accounts!',
];
