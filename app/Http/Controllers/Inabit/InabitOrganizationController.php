<?php

namespace App\Http\Controllers\Inabit;

use App\Http\Controllers\Controller;
use App\Services\InabitOrganizationService;
use Illuminate\Http\Request;

class InabitOrganizationController extends Controller
{
    public function __construct(
        protected InabitOrganizationService $orgs
    ) {}

    /**
     * GET /api/inabit/organizations
     *
     * Optional query param:
     *  - organization_id  -> return only that one
     *
     * If no organization_id is provided, return ALL organizations
     * for the API admin user.
     */
    public function index(Request $request)
    {
        $organizationId = $request->query('organization_id');

        if ($organizationId) {
            $org = $this->orgs->getOrganizationById($organizationId);

            if (! $org) {
                return response()->json([
                    'message' => "Organization with id {$organizationId} not found for this user.",
                ], 404);
            }

            return response()->json($org);
        }

        // No organization_id -> return all
        $orgs = $this->orgs->getUserOrganizations();

        return response()->json($orgs);
    }
}
