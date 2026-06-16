<?php

namespace AgencyCRM\PostTypes;

class LeadPostType
{
    public function register(): void
    {
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(): void
    {
        register_post_type('lead', [
            'labels' => [
                'name'          => 'Leads',
                'singular_name' => 'Lead',
                'add_new_item'  => 'Add New Lead',
                'edit_item'     => 'Edit Lead',
                'all_items'     => 'Leads',
            ],
            'public'        => false,
            'show_ui'       => true,
            // 'show_in_menu'  => 'agency-crm',
            'show_in_menu' => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-groups',
            'supports'      => ['title'],
        ]);
    }
}