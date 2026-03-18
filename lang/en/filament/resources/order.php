<?php

return [
    'navigation_label' => 'Requests',
    'label' => 'Request',

    'sections' => [
        'general' => 'General Information',
        'admin' => 'Administration',
        'products' => 'Requested Products',
    ],

    'fields' => [
        'user' => 'Requester',
        'service' => 'Service',
        'supplier' => 'Supplier',
        'quotation' => 'Quotation',
        'description' => 'Additional Details',
        'budget' => 'Budget Allocation',
        'status' => 'Status',
        'estimated_delivery_date' => 'Estimated Delivery Date',
        'total_amount' => 'Total Amount',
        'age' => 'Age (business days)',

        'lines' => [
            'label' => 'Order Line',
            'category' => 'Category',
            'reference' => 'Reference',
            'designation' => 'Designation',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price (cents)',
            'total_price' => 'Total Price (cents)',
        ],
    ],

    'actions' => [
        'process' => 'Process',
        'process_description' => 'Start processing this request. Please assign a budget allocation.',
        'mark_ordered' => 'Mark as Ordered',
        'mark_ordered_description' => 'Confirm the order has been placed with the supplier.',
        'mark_received' => 'Mark as Received',
        'mark_received_description' => 'Confirm the order has been received.',
        'close' => 'Close',
        'close_description' => 'Confirm the requester has picked up their order.',
        'cancel' => 'Cancel',
        'cancel_description' => 'Cancel this request.',
    ],
];
