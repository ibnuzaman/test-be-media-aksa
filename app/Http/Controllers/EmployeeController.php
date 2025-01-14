<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $employees = Employee::where('name', 'LIKE', "%{$request->name}%")
                ->when($request->division_id, function ($query) use ($request) {
                    return $query->where('division_id', $request->division_id);
                })->paginate(2);

            return response()->json(['status' => 'success', 'message' => 'Employees fetched successfully', 'data' => ['employees' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'image' => $employee->image,
                    'name' => $employee->name,
                    'phone' => $employee->phone,
                    'division' => [
                        'id' => $employee->division->id,
                        'name' => $employee->division->name,
                    ],
                    'position' => $employee->position,
                ];
            })], 'pagination' => ['current_page' => $employees->currentPage(), 'last_page' => $employees->lastPage(), 'per_page' => $employees->perPage(), 'total' => $employees->total(),]]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $division = Division::find($validated['division_id']);
            if (!$division) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Division ID is not valid'
                ], 422);
            }

            if (!$request->hasFile('image')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image file is missing'
                ], 422);
            }

            $fileNameImage = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $pathHeroImage = $request->file('image')->storeAs('image-employe', $fileNameImage, 'public');
            $ImageUrl = url('storage/' . $pathHeroImage);

            $employee = Employee::create([
                'name' => $validated['name'],
                'image' => $ImageUrl,
                'phone' => $validated['phone'],
                'division_id' => $validated['division_id'],
                'position' => $validated['position']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();            
            $employee = Employee::findOrFail($id);
            $division = Division::find($validated['division_id']);

            if (!$division) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Division ID is not valid'
                ], 422);
            }

            if ($request->hasFile('image')) {
                if (Storage::disk('public')->exists(str_replace(url('storage/'), '', $employee->image))) {
                    Storage::disk('public')->delete(str_replace(url('storage/'), '', $employee->image));
                }

                $fileNameImage = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $pathImage = $request->file('image')->storeAs('image-employe', $fileNameImage, 'public');
                $image = url('storage/' . $pathImage);
            } else {
                $image = $employee->image;
            }

            $employee->update([
                'name' => $validated['name'],
                'image' => $image,
                'phone' => $validated['phone'],
                'division_id' => $validated['division_id'],
                'position' => $validated['position']
            ]);         


            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',                
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $employee = Employee::findOrFail($id);
            if(Storage::disk('public')->exists(str_replace(url('storage/'), '', $employee->image))){
                Storage::disk('public')->delete(str_replace(url('storage/'), '', $employee->image));
            }
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ], 200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
