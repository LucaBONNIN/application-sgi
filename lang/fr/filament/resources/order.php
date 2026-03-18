<?php

return [
    'navigation_label' => 'Demandes',
    'label' => 'Demande',

    'sections' => [
        'general' => 'Informations générales',
        'admin' => 'Administration',
        'products' => 'Produits demandés',
    ],

    'fields' => [
        'user' => 'Demandeur',
        'service' => 'Service',
        'supplier' => 'Fournisseur',
        'quotation' => 'Devis',
        'description' => 'Précisions',
        'budget' => 'Clôture budgétaire',
        'status' => 'Statut',
        'estimated_delivery_date' => 'Date de livraison estimée',
        'total_amount' => 'Montant total',
        'age' => 'Ancienneté (jours ouvrés)',

        'lines' => [
            'label' => 'Produits demandés',
            'category' => 'Catégorie',
            'reference' => 'Référence',
            'designation' => 'Désignation',
            'quantity' => 'Quantité',
            'unit_price' => 'Prix unitaire (centimes)',
            'total_price' => 'Prix total (centimes)',
            'add_action' => 'Ajouter un produit'
        ],
    ],

    'actions' => [
        'process' => 'Traiter',
        'process_description' => 'Passer cette demande en traitement. Veuillez assigner une clôture budgétaire.',
        'mark_ordered' => 'Commander',
        'mark_ordered_description' => 'Confirmer que la commande a été passée auprès du fournisseur.',
        'mark_received' => 'Réceptionner',
        'mark_received_description' => 'Confirmer la réception de la commande.',
        'close' => 'Clôturer',
        'close_description' => 'Confirmer que le demandeur a récupéré sa commande.',
        'cancel' => 'Annuler',
        'cancel_description' => 'Annuler cette demande.',
    ],
];
