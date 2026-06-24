<?php

return [
    // General / System
    'system_alert' => 'System Alert',
    'test_title' => 'Test Notification',
    'test_message' => 'This is a test notification generated from the terminal.',
    'digest_title' => 'Daily Digest',
    'digest_msg' => 'Good morning! You have :contracts contracts expiring soon, and :cheques due cheques.',

    // Contracts
    'contract_expiring_title' => 'Contract Expiring Soon',
    'contract_expiring_msg' => 'Contract #:contract_no for property ":property" is expiring in :days days.',
    'contract_expired_title' => 'Contract Expired',
    'contract_expired_msg' => 'Contract #:contract_no for property ":property" has expired today.',
    'new_contract_title' => 'New Lease Contract',
    'new_contract_msg' => 'A new lease contract #:contract has been created.',
    'contract_cancelled_title' => 'Contract Cancelled',
    'contract_cancelled_msg' => 'Contract #:contract has been marked as cancelled!',
    'contract_reactivated_title' => 'Contract Reactivated',
    'contract_reactivated_msg' => 'Contract #:contract has been reactivated and is now active.',

    // Financial / Cheques
    'cheque_due_title' => 'Cheque Due Soon',
    'cheque_due_msg' => 'Cheque #:cheque_no for amount :amount is due in :days days.',
    'cheque_overdue_title' => 'Cheque Overdue',
    'cheque_overdue_msg' => 'Cheque #:cheque_no for amount :amount is overdue!',
    'cheque_cleared_title' => 'Cheque Cleared',
    'cheque_cleared_msg' => 'Cheque #:cheque_no for amount :amount has been cleared successfully.',
    'cheque_bounced_title' => 'Cheque Bounced',
    'cheque_bounced_msg' => 'Cheque #:cheque_no for amount :amount has bounced!',
    'cheque_reset_title' => 'Action Reverted',
    'cheque_reset_msg' => 'Action for cheque #:cheque_no for amount :amount was reverted back to pending.',
    'payment_collected_title' => 'Payment Collected',
    'payment_collected_msg' => 'A payment of :amount for contract :contract has been collected successfully.',
    'payment_reset_title' => 'Payment Action Reverted',
    'payment_reset_msg' => 'Payment of :amount for contract :contract was reverted back to pending.',
    'payment_deleted_title' => 'Payment Deleted',
    'payment_deleted_msg' => 'A payment of :amount for contract :contract has been deleted.',

    // Properties
    'property_vacant_title' => 'Property Available',
    'property_vacant_msg' => 'Property ":property" is now available.',

    // UI Elements
    'notifications' => 'Notifications',
    'mark_all_read' => 'Mark All as Read',
    'no_new_notifications' => 'No new notifications right now',
    'view_all' => 'View All Notifications',
    'new' => 'New',
    'just_now' => 'Just now',
    'tab_all' => 'All',
    'tab_financial' => 'Financial',
    'tab_contracts' => 'Contracts',
    'tab_system' => 'System',
    'collect_now' => 'Collect Now',

    // Actions & Confirmations
    'delete_selected' => 'Delete Selected',
    'delete_all' => 'Delete All',
    'view_details' => 'View Details',
    'mark_as_read' => 'Mark as Read',
    'confirm_delete_title' => 'Confirm Deletion',
    'confirm_delete_selected_text' => 'Are you sure you want to delete the selected notifications? This action cannot be undone.',
    'confirm_delete_all_text' => 'Are you sure you want to delete all notifications? This action cannot be undone.',
    'confirm_delete_single_text' => 'Are you sure you want to delete this notification? This action cannot be undone.',
    'overdue_debt_title' => 'Debt Limit Exceeded (Overdue)',
    'overdue_debt_body' => 'Customer :customer_name has an outstanding balance of :balance ILS and has exceeded the 10-day limit (Current debt age: :days days).',
];
