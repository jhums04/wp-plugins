<?php

namespace AgencyCRM\Admin;

use AgencyCRM\Bootstrap;
use AgencyCRM\Services\ActivityService;
use AgencyCRM\Repositories\ActivityRepository;

class LeadActivityMetaBox
{
        private ActivityService $activity;

        public function __construct()
        {
            $this->activity = new ActivityService(
                new ActivityRepository()
            );
        }
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
    $activities = $this->activity->getTimeline('lead', $post->ID);

    echo '<div style="padding:15px;">';

    if (empty($activities)) {
        echo '<p style="color:#888;">No activity yet.</p>';
        echo '</div>';
        return;
    }

    $lastDate = null;

    foreach ($activities as $activity) {

        $date = date('Y-m-d', strtotime($activity['created_at']));

        // Date separator
        if ($lastDate !== $date) {
            echo '<div style="margin:20px 0 10px; font-weight:bold; color:#555;">';
            echo date('F d, Y', strtotime($activity['created_at']));
            echo '</div>';
            $lastDate = $date;
        }

        $type = esc_html($activity['activity_type']);
        $desc = esc_html($activity['description']);
        $time = date('H:i', strtotime($activity['created_at']));

        // Badge color logic
        $color = '#6c757d';

        if ($type === 'stage_changed') $color = '#0d6efd';
        if ($type === 'lead_updated') $color = '#20c997';
        if ($type === 'test_event') $color = '#fd7e14';

        echo '<div style="
            display:flex;
            gap:10px;
            margin-bottom:12px;
        ">';

        // dot
        echo '<div style="
            width:10px;
            height:10px;
            background:' . $color . ';
            border-radius:50%;
            margin-top:6px;
            flex-shrink:0;
        "></div>';

        // card
        echo '<div style="
            background:#fff;
            border:1px solid #eee;
            padding:10px 12px;
            border-radius:8px;
            width:100%;
        ">';

        echo '<div style="
            font-size:12px;
            color:#999;
            margin-bottom:4px;
        ">' . $type . ' • ' . $time . '</div>';

        echo '<div style="font-size:14px;">' . $desc . '</div>';

        echo '</div>';

        echo '</div>';
    }

    echo '</div>';
}
}