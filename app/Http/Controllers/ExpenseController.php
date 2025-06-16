<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Services\ExpenseService;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\ApproveExpenseRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpenseController extends Controller
{
    protected $expenseService;
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * @OA\Post(
     *     path="/api/expense",
     *     summary="Create new expense",
     *     tags={"Expense"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="integer", example=1000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Expense created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="amount", type="integer", example=1000),
     *             @OA\Property(property="status_id", type="integer", example=1),
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
    public function store(StoreExpenseRequest $request)
    {
        $expense = $this->expenseService->store($request->validated());
        return response()->json($expense, 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/expense/{id}/approve",
     *     summary="Approve expense",
     *     tags={"Expense"},
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
     *             @OA\Property(property="approver_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense approved",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="amount", type="integer", example=1000),
     *             @OA\Property(property="status_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", example="2024-06-16T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-16T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function approve(ApproveExpenseRequest $request, $id)
    {

        $expense = Expense::findOrFail($id);

        $updatedExpense = $this->expenseService->update($expense, $request->validated());

        return response()->json($updatedExpense);
    }

    /**
     * @OA\Get(
     *     path="/api/expense/{id}",
     *     summary="Get expense by ID",
     *     tags={"Expense"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="amount", type="integer", example=1000),
     *             @OA\Property(property="status_id", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", example="2024-06-16T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-16T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function show(Expense $id)
    {
        return new ExpenseResource($id);
    }
}
