<?php

namespace AgencyCRM\Repositories;

class ActivityRepository
{
    private string $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'crm_activities';
    }

    public function create(array $data): void
    {
        global $wpdb;

        $wpdb->insert($this->table, [
            'entity_type'   => $data['entity_type'],
            'entity_id'     => $data['entity_id'],
            'activity_type' => $data['activity_type'],
            'description'   => $data['description'],
            'created_by'    => get_current_user_id(),
            'created_at'    => current_time('mysql'),
        ]);
    }

    public function getByEntity(string $type, int $id): array
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$this->table}
                 WHERE entity_type = %s AND entity_id = %d
                 ORDER BY created_at DESC",
                $type,
                $id
            ),
            ARRAY_A
        );
    }
}