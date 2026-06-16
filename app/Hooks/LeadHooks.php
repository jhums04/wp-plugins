<?php

namespace AgencyCRM\Hooks;

use AgencyCRM\Services\ActivityService;
use AgencyCRM\Repositories\ActivityRepository;
use AgencyCRM\Constants\ActivityTypes;

class LeadHooks
{
    public function register(): void
    {
        add_action('wp_insert_post', [$this, 'handleLeadCreated'], 10, 3);
    }

    public function handleLeadCreated($postId, $post, $update): void
    {
        if ($post->post_type !== 'lead') return;
        if ($update) return;

        $activityService = new ActivityService(
            new ActivityRepository()
        );

        $activityService->log([
            'entity_type'   => 'lead',
            'entity_id'     => $postId,
            'activity_type' => ActivityTypes::LEAD_CREATED,
            'description'   => 'Lead created',
        ]);
    }
}