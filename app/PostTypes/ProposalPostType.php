<?php

namespace AgencyCRM\PostTypes;

class ProposalPostType
{
    public function register(): void
    {
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(): void
    {
        register_post_type('proposal', [
            'labels' => [
                'name'          => 'Proposals',
                'singular_name' => 'Proposal',
                'add_new_item'  => 'Add New Proposal',
                'edit_item'     => 'Edit Proposal',
                'all_items'     => 'Proposals',
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_menu' => true,
            'show_in_rest'  => true,
            'menu_icon'     => 'dashicons-groups',
            'supports'      => ['title'],
        ]);
    }
}
