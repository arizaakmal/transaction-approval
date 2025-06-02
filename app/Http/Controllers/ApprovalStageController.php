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
    public function store(StoreApprovalStageRequest $request)
    {
        $approvalStage = $this->approvalStageService->store($request->all());
        return response()->json($approvalStage, 201);
    }

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
