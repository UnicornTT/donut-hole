<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Integer;

class DepartmentController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function get()
    {
        return DepartmentsResource::collection(
            Department::select(['id', 'name'])
                ->withCount('employees')
                ->withMax('employees', 'salary')
                ->paginate(10)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Department::validate());

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $department = new Department([
            'name' => $request->name,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $department->save();

        new DepartmentsResource($department);

        return response()->json('Department created');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $department = Department::find((int)$id);

        if(empty($department)){
            return response()->json('Department not found', 422);
        }

        $department->update([
            'name' => $request->name,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json('Department updated');
    }

    /**
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $department = Department::find((int)$id);

        if(empty($department)){
            return response()->json('Department not found', 422);
        }

        if ($department->Employees()->count() > 0) {
            return response()->json('This department cannot be deleted', 451);
        }

        $department->delete();

        return response()->json('Department removed');
    }
}
