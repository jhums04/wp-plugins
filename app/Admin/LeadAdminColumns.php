<?php

namespace AgencyCRM\Admin;

class LeadAdminColumns
{
    public function register(): void
    {
        add_filter(
            'manage_lead_posts_columns',
            [$this, 'columns']
        );

        add_action(
            'manage_lead_posts_custom_column',
            [$this, 'columnValues'],
            10,
            2
        );
    }

    public function columns(array $columns): array
    {
        return [
            'cb' => $columns['cb'],
            'title' => 'Company Name',
            'contact_name' => 'Contact Name',
            'email' => 'Email',
            'stage' => 'Stage',
            'budget' => 'Budget',
            'assigned_user' => 'Assigned User',
            'date' => 'Date',
        ];
    }

    public function columnValues(
        string $column,
        int $postId
    ): void {

        switch ($column) {

            case 'contact_name':
                echo esc_html(
                    get_post_meta(
                        $postId,
                        'contact_name',
                        true
                    )
                );
                break;

            case 'email':
                echo esc_html(
                    get_post_meta(
                        $postId,
                        'email',
                        true
                    )
                );
                break;

            case 'stage':
                echo esc_html(
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            get_post_meta(
                                $postId,
                                'stage',
                                true
                            )
                        )
                    )
                );
                break;

            case 'budget':
                echo esc_html(
                    number_format(
                        (float) get_post_meta(
                            $postId,
                            'budget',
                            true
                        ),
                        2
                    )
                );
                break;

            case 'assigned_user':

                $userId = get_post_meta(
                    $postId,
                    'assigned_user',
                    true
                );

                $user = get_userdata($userId);

                echo $user
                    ? esc_html($user->display_name)
                    : '-';

                break;
        }
    }
}