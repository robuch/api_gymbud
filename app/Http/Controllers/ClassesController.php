<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesCollection;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Http\Resources\ClassesResource;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;



class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $class = Classes::with('category')->paginate(5);

        return response()->json(['data' => new ClassesCollection($class), 200, 'message' => 'Success']);
       // return ClassesResource::collection($class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate the request data
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y/m/d|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'type_id' => 'required|exists:types,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check if class already exists for that day and time
        if ($request->date == DB::table('classes')->where('date', $request->date)->exists()) {
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            $period = CarbonPeriod::create($start,'1 minute', $end)->toArray();

            foreach ($period as $time) {
                if ($time == DB::table('classes')->where('start_time', $time)->exists()) {
                    return response()->json(['message' => 'Class already exists'], 409);
                }

                if ($time == DB::table('classes')->where('end_time', $time)->exists()) {
                    return response()->json(['message' => 'Class already exists'], 409);
                }
            }
        }

        //if validation success
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/classes', $image->hashName());

        //create class
        try {
            $class = Classes::create([
                'image' => ('classes/'.$image->hashName()),
                'name' => $request->name,
                'description' => $request->description,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'location' => $request->location,
                'instructor_id' => $request->instructor_id,
                'type_id' => $request->type_id,
                'category_id' => $request->category_id,
                'status' => $request->status,
            ]);

            //return response
            return  response()->json([
                'message' => 'Class created successfully',
                'data' => new ClassesResource($class)],
                 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }


        //return response

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        return response()->json(['data' => new ClassesResource($class), 200, 'Success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date_format:Y/m/d|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string',
            'instructor_id' => 'required|integer|exists:instructors,id',
            'type_id' => 'required|integer|exists:types,id',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $class = Classes::find($id);

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        if ($request->start_time != $class->start_time or $request->end_time != $class->end_time or $request->date != $class->date) {
            if ($request->date == DB::table('classes')->where('date', $request->date)->exists()) {
                $start = Carbon::parse($request->start_time);
                $end = Carbon::parse($request->end_time);

                $period = CarbonPeriod::create($start,'1 minute', $end)->toArray();

                foreach ($period as $time) {
                    if ($time == DB::table('classes')->where('start_time', $time)->exists()) {
                        return response()->json(['message' => 'Class already exists'], 409);
                    }

                    if ($time == DB::table('classes')->where('end_time', $time)->exists()) {
                        return response()->json(['message' => 'Class already exists'], 409);
                    }
                }
            }
        }


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/classes', $image->hashName());

            Storage::delete('public/'.$class->image);

        try {
            $class->update([
                'image' => ('classes/'.$image->hashName()),
                'name' => $request->name,
                'description' => $request->description,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'location' => $request->location,
                'instructor_id' => $request->instructor_id,
                'type_id' => $request->type_id,
                'category_id' => $request->category_id,
                'status' => $request->status,
            ]);

            return response()->json(['message' => 'Class updated successfully', 'data' => new ClassesResource($class)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }


        } else {
            try {
                $class->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'date' => $request->date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'capacity' => $request->capacity,
                    'price' => $request->price,
                    'location' => $request->location,
                    'instructor_id' => $request->instructor_id,
                    'type_id' => $request->type_id,
                    'category_id' => $request->category_id,
                    'status' => $request->status,
                ]);

                return response()->json(['message' => 'Class updated successfully', 'data' => new ClassesResource($class)], 200);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        try {
            $class->delete();
            return response()->json(['message' => 'Class deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

