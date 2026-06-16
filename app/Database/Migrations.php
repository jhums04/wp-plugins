<?php

namespace AgencyCRM\Database;

class Migrations
{
    public function run(): void
    {
        global $wpdb;

        $table = $wpdb->prefix . 'crm_activities';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

            entity_type VARCHAR(50) NOT NULL,
            entity_id BIGINT UNSIGNED NOT NULL,

            activity_type VARCHAR(50) NOT NULL,
            description TEXT NOT NULL,

            created_by BIGINT UNSIGNED NULL,
            created_at DATETIME NOT NULL
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}