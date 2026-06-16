<?php

namespace AgencyCRM\Services;

use AgencyCRM\Repositories\ActivityRepository;

class ActivityService
{
    public function __construct(
        private ActivityRepository $repo
    ) {}

    public function log(array $data): void
    {
        $this->repo->create([
            'entity_type'   => $data['entity_type'],
            'entity_id'     => $data['entity_id'],
            'activity_type' => $data['activity_type'],
            'description'   => $data['description'],
        ]);
    }

    public function exists(string $type, int $id, string $activityType): bool
    {
        return $this->repo->exists($type, $id, $activityType);
    }

    public function getTimeline(string $type, int $id): array
    {
        return $this->repo->getByEntity($type, $id);
    }
}