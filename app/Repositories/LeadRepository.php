<?php

namespace AgencyCRM\Repositories;

class LeadRepository
{
    public function create(array $data): int
    {
        $postDate = !empty($data['post_date'])
            ? $data['post_date'] . ' 00:00:00'
            : current_time('mysql');

        // ✅ condition: if post_date exists, force stage = "new"
        if (!empty($data['post_date'])) {
            $data['stage'] = 'new';
        }

        $leadId = wp_insert_post([
            'post_type'      => 'lead',
            'post_title'     => $data['company_name'],
            'post_status'    => 'publish',
            'post_date'      => $postDate,
            'post_date_gmt'  => get_gmt_from_date($postDate),
        ]);

        if (is_wp_error($leadId)) {
            throw new \Exception($leadId->get_error_message());
        }

        $this->saveMeta($leadId, $data);

        return $leadId;
    }

    public function update(int $leadId, array $data): void
    {
        wp_update_post([
            'ID'         => $leadId,
            'post_title' => $data['company_name'],
        ]);

        $this->saveMeta($leadId, $data);
    }

    public function find(int $leadId): ?array
    {
        $post = get_post($leadId);

        if (!$post) {
            return null;
        }

        return [
            'id' => $post->ID,
            'company_name' => $post->post_title,
            'contact_name' => get_post_meta($leadId, 'contact_name', true),
            'email' => get_post_meta($leadId, 'email', true),
            'phone' => get_post_meta($leadId, 'phone', true),
            'source' => get_post_meta($leadId, 'source', true),
            'stage' => get_post_meta($leadId, 'stage', true),
            'budget' => get_post_meta($leadId, 'budget', true),
            'assigned_user' => get_post_meta($leadId, 'assigned_user', true),
        ];
    }

    public function delete(int $leadId): bool
    {
        return (bool) wp_delete_post($leadId, true);
    }

    private function saveMeta(int $leadId, array $data): void
    {
        $fields = [
            'contact_name',
            'email',
            'phone',
            'source',
            'stage',
            'budget',
            'assigned_user',
            'message'
        ];

        foreach ($fields as $field) {
            update_post_meta(
                $leadId,
                $field,
                $data[$field] ?? ''
            );
        }
    }

    public function findLeadsWithCallNotes(): array
    {
        // Get all lead IDs that already have proposals
        $proposalQuery = new \WP_Query([
            'post_type'      => 'proposal',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        $leadIdsWithProposal = [];

        foreach ($proposalQuery->posts as $proposalId) {
            $leadId = get_post_meta(
                $proposalId,
                'lead_id',
                true
            );

            if (!empty($leadId)) {
                $leadIdsWithProposal[] = (int) $leadId;
            }
        }

        $query = new \WP_Query([
            'post_type'      => 'lead',
            'post_status'    => 'publish',
            'posts_per_page' => -1,

            // Exclude leads that already have proposals
            'post__not_in'   => $leadIdsWithProposal,

            'meta_query'     => [
                'relation' => 'AND',

                [
                    'key'   => 'stage',
                    'value' => 'discovery',
                ],

                [
                    'key'     => 'call_notes',
                    'value'   => '',
                    'compare' => '!=',
                ],
            ],
        ]);

        $results = [];

        foreach ($query->posts as $post) {

            $results[] = [
                'id'           => $post->ID,
                'company_name' => $post->post_title,

                'contact_name' => get_post_meta(
                    $post->ID,
                    'contact_name',
                    true
                ),

                'email' => get_post_meta(
                    $post->ID,
                    'email',
                    true
                ),

                'phone' => get_post_meta(
                    $post->ID,
                    'phone',
                    true
                ),

                'post_date' => get_the_date(
                    'Y-m-d',
                    $post->ID
                ),

                'message' => get_post_meta(
                    $post->ID,
                    'message',
                    true
                ),

                'budget' => get_post_meta(
                    $post->ID,
                    'budget',
                    true
                ),
                'service' => get_post_meta(
                    $post->ID,
                    'service',
                    true
                ),

                'call_notes' => get_post_meta(
                    $post->ID,
                    'call_notes',
                    true
                ),
            ];
        }

        return $results;
    }

    public function findByStage(string $stage): array
    {
        $query = new \WP_Query([
            'post_type'      => 'lead',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => 'stage',
                    'value' => $stage,
                ]
            ]
        ]);

        $results = [];

        foreach ($query->posts as $post) {

            $results[] = [
                'id'           => $post->ID,
                'company_name' => $post->post_title,
                'contact_name' => get_post_meta(
                    $post->ID,
                    'contact_name',
                    true
                ),
                'email' => get_post_meta(
                    $post->ID,
                    'email',
                    true
                ),
                'phone' => get_post_meta(
                    $post->ID,
                    'phone',
                    true
                ),
                'post_date' => get_the_date('Y-m-d', $post->ID),
                'message' => get_post_meta(
                    $post->ID,
                    'message',
                    true
                ),
                'budget' => get_post_meta(
                    $post->ID,
                    'budget',
                    true
                ),
            ];
        }

        return $results;
    }
}
