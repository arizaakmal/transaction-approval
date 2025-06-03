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

    public function store(StoreExpenseRequest $request)
    {
        $expense = $this->expenseService->store($request->validated());
        return response()->json($expense, 201);
    }

    public function approve(ApproveExpenseRequest $request, $id)
    {

        $expense = Expense::findOrFail($id);

        $updatedExpense = $this->expenseService->update($expense, $request->validated());

        return response()->json($updatedExpense);
    }

    // public function show($id)
    // {
    //     try {
    //         $expense = $this->expenseService->show($id);
    //         return new ExpenseResource($expense);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'message' => 'Expense not found.',
    //             'errors' => [
    //                 'id' => ['Expense not found.']
    //             ]
    //         ], 404);
    //     }
    // }

    public function show(Expense $id)
    {
        return new ExpenseResource($id);
    }
}
