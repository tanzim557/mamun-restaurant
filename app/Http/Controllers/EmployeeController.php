<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $salary = floatval($data['salary'] ?? 0);
        $salaryPaid = floatval($data['salaryPaid'] ?? 0);
        // Default salary_due to (salary - salary_paid) if not explicitly set
        $salaryDue = isset($data['salaryDue']) ? floatval($data['salaryDue']) : max(0, $salary - $salaryPaid);

        $employee = Employee::create([
            'name' => $data['name'] ?? '',
            'position' => $data['position'] ?? '',
            'phone' => $data['phone'] ?? null,
            'salary' => $salary,
            'salary_paid' => $salaryPaid,
            'salary_due' => $salaryDue,
            'join_date' => $data['joinDate'] ?? null,
            'note' => $data['note'] ?? null,
        ]);
        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['error' => 'Employee not found'], 404);

        $data = $request->all();
        if (isset($data['name'])) $employee->name = $data['name'];
        if (isset($data['position'])) $employee->position = $data['position'];
        if (isset($data['phone'])) $employee->phone = $data['phone'];
        if (isset($data['salary'])) $employee->salary = floatval($data['salary']);
        if (isset($data['salaryPaid'])) $employee->salary_paid = floatval($data['salaryPaid']);
        if (isset($data['salaryDue'])) $employee->salary_due = floatval($data['salaryDue']);
        if (isset($data['joinDate'])) $employee->join_date = $data['joinDate'];
        if (isset($data['note'])) $employee->note = $data['note'];
        $employee->save();
        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['error' => 'Employee not found'], 404);
        $employee->delete();
        return response()->json(['message' => 'Employee deleted']);
    }
}
