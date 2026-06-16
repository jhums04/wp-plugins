<?php

namespace AgencyCRM\Services;

use AgencyCRM\Repositories\LeadRepository;

class LeadService
{
    public function __construct(
        private LeadRepository $leadRepository
    ) {
    }

    public function createLead(array $data): int
    {
        $this->validate($data);

        $data['stage'] = $data['stage'] ?? 'new';

        return $this->leadRepository->create($data);
    }

    public function updateLead(
        int $leadId,
        array $data
    ): void {
        $this->validate($data);

        $this->leadRepository->update(
            $leadId,
            $data
        );
    }

    public function getLead(int $leadId): ?array
    {
        return $this->leadRepository->find($leadId);
    }

    private function validate(array $data): void
    {
        if (empty($data['company_name'])) {
            throw new \Exception(
                'Company Name is required'
            );
        }

        if (empty($data['email'])) {
            throw new \Exception(
                'Email is required'
            );
        }

        if (!filter_var(
            $data['email'],
            FILTER_VALIDATE_EMAIL
        )) {
            throw new \Exception(
                'Invalid email address'
            );
        }
    }
}