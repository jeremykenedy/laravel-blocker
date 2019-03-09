<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Blocker Blades Language Lines - laravelblocker
    |--------------------------------------------------------------------------
    */

    'blocked-items-title'   => 'Blocked Items',
    'blocked-item-title'    => 'Blocked Item: <strong>:name</strong>',
    'na'                    => 'N/A',
    'none'                  => 'None',

    'titles' => [
        'show-blocked'      => 'Blocked Items',
        'create-blocked'    => 'Create Blocked Item',
    ],

    'buttons' => [
        'create-new-blocked'    => 'Create New',
        'show-deleted-blocked'  => 'Show Deleted',
        'back-to-blocked'       => 'Back to Blocked',
        'show'                  => '<span class="hidden-xs hidden-sm">Show </span><i class="fa fa-eye fa-fw" aria-hidden="true"></i>',
        'edit'                  => '<span class="hidden-xs hidden-sm">Edit </span><i class="fa fa-pencil fa-fw" aria-hidden="true"></i>',
        'delete'                => '<span class="hidden-xs hidden-sm">Delete </span><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>',
    ],

    'tooltips' => [
        'delete'        => 'Delete',
        'show'          => 'Show',
        'edit'          => 'Edit',
        'create-new'    => 'Create New Blocked',
        'back-blocked'  => 'Back to blocked',
        'submit-search' => 'Submit Blocked Search',
        'clear-search'  => 'Clear Search Results',
    ],

    'blocked-table' => [
        'caption'   => '{1} :blockedcount block total|[2,*] :blockedcount total blocks',
        'id'        => 'ID',
        'type'      => 'Type',
        'value'     => 'Value',
        'note'      => 'Note',
        'userId'    => 'UserID',
        'createdAt' => 'Created',
        'updatedAt' => 'Updated',
        'actions'   => 'Actions',
    ],

    'forms' => [
        'search-blocked-ph' => 'Search Blocked',
        'blockedTypeLabel'  => 'Blocked Type',
        'blockedTypeSelect' => 'Select Blocked Type',
        'blockedValueLabel' => 'Blocked Value',
        'blockedValuePH'    => 'Blocked Value',
        'blockedNoteLabel'  => 'Blocked Note',
        'blockedNotePH'     => 'Type Blocked Note',
        'blockedUserLabel'  => 'Blocked User',
        'blockedUserSelect' => 'Select Blocked User',

    ],

    'search'  => [
        'title'             => 'Showing Search Results',
        'found-footer'      => ' Record(s) found',
        'no-results'        => 'No Results',
        'search-users-ph'   => 'Search Blocked',
        'required'          => 'Search term is required',
        'string'            => 'Search term has invalid characters',
        'max'               => 'Search term has too many characters - 255 allowed',
    ],

    'modals' => [
        'delete_blocked_title'          => 'Delete blocked item',
        'delete_blocked_message'        => 'Are you sure you want to delete :blocked?',
        'delete_blocked_btn_cancel'     => 'Cancel',
        'delete_blocked_btn_confirm'    => 'Confirm Delete',
    ],

    'messages' => [
        'blocked-creation-success'  => 'Successfully created blocked item',
        'delete-success'            => 'Successfully deleted blocked item',
    ],

    'validation' => [
        'blockedTypeRequired'   => 'Blocked Type is required.',
        'blockedValueRequired'  => 'Blocked Value is required.',
        'blockedExists'         => 'The :attribute already exists.',
        'email'                 => 'Must be a valid formed email address.',
    ],

];
