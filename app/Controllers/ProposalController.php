<?php

namespace AgencyCRM\Controllers;

use AgencyCRM\Repositories\ProposalRepository;
use WP_REST_Request;
use WP_REST_Response;

class ProposalController
{
    private ProposalRepository $repository;

    public function __construct()
    {
        $this->repository = new ProposalRepository();
    }

    public function register(): void
    {
        add_action(
            'rest_api_init',
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(): void
    {
        register_rest_route(
            'agency-crm/v1',
            '/proposals',
            [
                'methods' => 'POST',
                'callback' => [$this, 'createProposal'],
                'permission_callback' => '__return_true',
            ]
        );
    }

    public function createProposal(
        WP_REST_Request $request
    ): WP_REST_Response {

        $data = $request->get_json_params();

        // Handle array payload
        if (isset($data[0])) {
            $data = $data[0];
        }

        $proposalId = $this->repository->create($data);

        return new WP_REST_Response(
            [
                'message' => 'Proposal created successfully',
                'proposal_id' => $proposalId
            ],
            200
        );
    }
}
