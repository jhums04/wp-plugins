<?php

namespace AgencyCRM\Services;

use AgencyCRM\Repositories\ActivityRepository;

class ActivityService
{
    public function __construct(
        private ActivityRepository $repo
    ) {}

    public function log(
        string $entityType,
        int $entityId,
        string $type,
        string $description
    ): void {

        $this->repo->create([
            'entity_type'   => $entityType,
            'entity_id'     => $entityId,
            'activity_type' => $type,
            'description'   => $description,
        ]);
    }

    public function getTimeline(string $type, int $id): array
    {
        return $this->repo->getByEntity($type, $id);
    }
}