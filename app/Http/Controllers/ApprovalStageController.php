<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalStage;
use App\Services\ApprovalStageService;
use App\Http\Requests\StoreApprovalStageRequest;
use App\Http\Requests\UpdateApprovalStageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalStageController extends Controller
{
    protected $approvalStageService;
    public function __construct(ApprovalStageService $approvalStageService)
    {
        $this->approvalStageService = $approvalStageService;
    }

    /**
     * @OA\Post(
     *     path="/api/approval-stages",
     *     summary="Create approval stage",
     *     tags={"ApprovalStage"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"approver_id"},
     *             @OA\Property(property="approver_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Approval stage created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="approver_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", example="2024-06-16T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-16T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreApprovalStageRequest $request)
    {
        $approvalStage = $this->approvalStageService->store($request->all());
        return response()->json($approvalStage, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/approval-stages/{id}",
     *     summary="Update approval stage",
     *     tags={"ApprovalStage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"approver_id"},
     *             @OA\Property(property="approver_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Approval stage updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="approver_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", example="2024-06-16T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-16T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid approval stage ID"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdateApprovalStageRequest $request, int $id)
    {
        try {
            $approvalStage = ApprovalStage::findOrFail($id);

            $updatedApprovalStage = $this->approvalStageService->update($approvalStage, $request->validated());

            return response()->json($updatedApprovalStage, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Invalid approval stage ID.',
            ], 404);
        }
    }
}
