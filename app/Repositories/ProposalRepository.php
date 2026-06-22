<?php

namespace AgencyCRM\Repositories;

class ProposalRepository
{
    public function create(array $data): int
    {
        $leadId = (int) ($data['lead_id'] ?? 0);

        if (!$leadId) {
            throw new \Exception('Lead ID is required.');
        }

        $companyName = get_the_title($leadId);

        if (empty($companyName)) {
            throw new \Exception('Lead not found.');
        }

        $proposalId = wp_insert_post([
            'post_type'    => 'proposal',
            'post_title'   => $companyName . ' Proposal',
            'post_content' => $data['proposal'] ?? '',
            'post_status'  => 'publish',
        ]);

        if (is_wp_error($proposalId)) {
            throw new \Exception(
                $proposalId->get_error_message()
            );
        }

        $this->saveMeta($proposalId, $data);

        error_log("{$proposalId} created successfully");
        return $proposalId;
    }

    public function find(int $proposalId): ?array
    {
        $post = get_post($proposalId);

        if (!$post) {
            return null;
        }

        return [
            'id'       => $post->ID,
            'title'    => $post->post_title,
            'content'  => $post->post_content,
            'lead_id'  => get_post_meta(
                $proposalId,
                'lead_id',
                true
            ),
            'status'   => get_post_meta(
                $proposalId,
                'status',
                true
            ),
            'version'  => get_post_meta(
                $proposalId,
                'version',
                true
            ),
        ];
    }

    public function update(
        int $proposalId,
        array $data
    ): void {

        wp_update_post([
            'ID'           => $proposalId,
            'post_content' => $data['proposal'] ?? '',
        ]);

        $this->saveMeta($proposalId, $data);
    }

    public function delete(int $proposalId): bool
    {
        return (bool) wp_delete_post(
            $proposalId,
            true
        );
    }

    private function saveMeta(
        int $proposalId,
        array $data
    ): void {

        update_post_meta(
            $proposalId,
            'lead_id',
            $data['lead_id'] ?? ''
        );

        update_post_meta(
            $proposalId,
            'status',
            $data['status'] ?? 'draft'
        );

        update_post_meta(
            $proposalId,
            'version',
            $data['version'] ?? 1
        );
    }
}
