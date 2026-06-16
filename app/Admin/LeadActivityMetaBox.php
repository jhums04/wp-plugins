<?php

namespace AgencyCRM\Admin;

use AgencyCRM\Bootstrap;

class LeadActivityMetaBox
{
    public function register(): void
    {
        add_action('add_meta_boxes', [$this, 'add']);
    }

    public function add(): void
    {
        add_meta_box(
            'lead_activity',
            'Activity Timeline',
            [$this, 'render'],
            'lead',
            'normal',
            'low'
        );
    }

    public function render($post): void
    {
        $activities = Bootstrap::$activity->getTimeline('lead', $post->ID);

        if (empty($activities)) {
            echo '<p>No activity yet.</p>';
            return;
        }

        echo '<div style="padding:10px;">';

        foreach ($activities as $activity) {
            echo '<div style="margin-bottom:10px;">';
            echo '<strong>' . esc_html($activity['description']) . '</strong><br>';
            echo '<small>' . esc_html($activity['created_at']) . '</small>';
            echo '</div>';
        }

        echo '</div>';
    }
}