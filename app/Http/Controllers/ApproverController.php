<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApproverRequest;
use App\Services\ApproverService;

use Illuminate\Http\Request;

class ApproverController extends Controller
{
    protected $approverService;

    public function __construct(ApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    /**
     * @OA\Post(
     *     path="/api/approvers",
     *     summary="Create new approver",
     *     tags={"Approver"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Ana")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Approver created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Ana")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreApproverRequest $request)
    {
        $approver = $this->approverService->store($request->validated());
        return response()->json($approver, 201);
    }
}
