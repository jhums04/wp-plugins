<?php

namespace AgencyCRM\Admin;

class ProposalAdminColumns
{
    public function register(): void
    {
        add_filter(
            'manage_proposal_posts_columns',
            [$this, 'columns']
        );

        add_action(
            'manage_proposal_posts_custom_column',
            [$this, 'columnValues'],
            10,
            2
        );
    }

    public function columns(array $columns): array
    {
        return [
            'cb'       => $columns['cb'],
            'title'    => 'Proposal',
            'lead_id'  => 'Lead',
            'status'   => 'Status',
            'version'  => 'Version',
            'preview'  => 'Preview',
            'date'     => 'Date',
        ];
    }

    public function columnValues(
        string $column,
        int $postId
    ): void {

        switch ($column) {

            case 'lead_id':

                $leadId = get_post_meta(
                    $postId,
                    'lead_id',
                    true
                );

                echo esc_html($leadId);

                break;

            case 'status':

                $status = get_post_meta(
                    $postId,
                    'status',
                    true
                );

                $labels = [
                    'draft'     => 'Draft',
                    'reviewing' => 'Reviewing',
                    'sent'      => 'Sent',
                    'approved'  => 'Approved',
                    'rejected'  => 'Rejected',
                ];

                echo esc_html(
                    $labels[$status] ?? $status
                );

                break;

            case 'version':

                echo esc_html(
                    get_post_meta(
                        $postId,
                        'version',
                        true
                    )
                );

                break;

            case 'preview':

                $post = get_post($postId);

                if (!$post) {
                    echo '-';
                    break;
                }

                echo esc_html(
                    wp_trim_words(
                        strip_tags($post->post_content),
                        12
                    )
                );

                break;
        }
    }
}
