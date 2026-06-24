<?php
return [
    // Define the core modules that support CRUD permissions
    'modules' => [
        'stores' => 'global.stores',
        'settings' => 'global.settings',
        'roles' => 'global.roles',
        'users' => 'global.users',
        'departments' => 'global.departments',
        'bank_accounts' => 'global.bank_accounts',
        'notifications' => 'notifications.notifications',
        'notebook' => 'notebook.store_notebook',
        'store_customers' => 'store_customers.store_customers',
        'store_transactions' => 'store_transactions.store_transactions',
        'payment_entities' => 'payment_entities.payment_entities',
        'store_withdrawals' => 'store_withdrawals.store_withdrawals',
    ],

    // Define custom operations for specific modules that don't follow standard CRUD
    'custom_operations' => [
        'reports' => [],
        'notifications' => [
            'read' => 'general.show', // Only read permission is needed for now
        ],
    ],

    // Define icons for each module to keep Blade files clean
    'module_icons' => [
        'dashboard' => 'fas fa-th-large fa-fw',
        'settings' => 'fas fa-cogs fa-fw',
        'roles' => 'fas fa-user-shield fa-fw',
        'users' => 'fas fa-users-cog fa-fw',
        'departments' => 'fas fa-sitemap fa-fw',
        'stores' => 'fas fa-city fa-fw',
        'bank_accounts' => 'fas fa-university fa-fw',
        'reports' => 'fas fa-chart-line fa-fw',
        'notifications' => 'fas fa-bell fa-fw',
        'notebook' => 'fas fa-book fa-fw',
        'store_customers' => 'fas fa-users fa-fw',
        'store_transactions' => 'fas fa-exchange-alt fa-fw',
        'payment_entities' => 'fas fa-landmark fa-fw',
    ],

    // Define the CRUD operations available for these modules
    'crud_operations' => [
        'read' => 'general.show',
        'create' => 'general.add',
        'update' => 'general.update',
        'delete' => 'general.delete',
    ],
];
