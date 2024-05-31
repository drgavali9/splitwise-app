<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExpenseRequest;
use App\Http\Requests\Api\V1\ExpensesListRequest;
use App\Http\Resources\Api\V1\ExpenseResource;
use App\Models\Expense;
use App\Models\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(ExpensesListRequest $request)
    {
        //        $this->authorize('viewAny', Expense::class);

        $expenses = Expense::query()
            ->where('group_id', $request->group_id)
            ->paginate(request()->get('perPage', 20));

        return ExpenseResource::collection($expenses);
    }

    public function store(ExpenseRequest $request)
    {
        //        $this->authorize('create', Expense::class);

        $group = Group::find($request->group_id);

        $expenses = $group->expenses()->create($request->validated());

        return ExpenseResource::make($expenses);
    }

    public function show(Expense $expense)
    {
        //        $this->authorize('view', $expense);

        return ExpenseResource::make($expense->loadMissing(['expenseSplits']));
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        //        $this->authorize('update', $expense);

        $validateData = Arr::except($request->validated(), ['group_id']);

        $expense->update($validateData);

        return ExpenseResource::make($expense->loadMissing(['expenseSplits']));
    }

    public function destroy(Expense $expense)
    {
        //        $this->authorize('delete', $expense);

        $expense->delete();

        return response()->json(['message' => 'Expenses delete successfully..']);
    }
}
