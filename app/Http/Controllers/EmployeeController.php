<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{

    /**
     * @return  JsonResponse
     */
    public function get(): JsonResponse
    {
        $data = EmployeeResource::collection(Employee::paginate(10));

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), Employee::validate());

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = new Employee([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'gender' => $request->gender,
            'salary' => $request->salary,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $employee->save();
        $employee->departments()->attach($request->departments);

        new EmployeeResource($employee);

        return response()->json('Employee created.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'required',
            'salary' => 'integer|required',
            'departments' => 'array|required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::find((int)$id);

        if(empty($employee)){
            return response()->json('Employee not found', 422);
        }

        $employee->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'gender' => $request->gender,
            'salary' => $request->salary,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $employee->departments()->sync($request->departments);

        new EmployeeResource($employee);

        return response()->json('Employee updated');
    }

    /**
     * @return JsonResponse
     */
    public function delete($id): JsonResponse {

        $employee = Employee::find((int)$id);

        if(empty($employee)){
            return response()->json('Department not found', 422);
        }

        $employee->departments()->detach();
        $employee->delete();

        return response()->json('Employee removed');
    }
}
