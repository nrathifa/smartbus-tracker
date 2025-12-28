<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusLog;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BusController extends Controller
{
    // --- VIEWS ---

    public function index()
    {
        $buses = Bus::all();
        return view('admin.businfo', compact('buses'));
    }

    public function userTrack()
    {
        $buses = Bus::all();
        return view('user.track', compact('buses'));
    }

    public function show($id)
    {
        $bus = Bus::findOrFail($id);
        return view('user.bus', compact('bus'));
    }

    // --- API & LOGIC ---

    public function liveBusCount()
    {
        $buses = Bus::all();
        return response()->json($buses);
    }

    public function handleRfidScan(Request $request)
    {
        try {
            $uid = $request->input('uid');

            // 1. Validate Input
            if (!$uid) {
                return response()->json(['message' => 'Error: No UID'], 400);
            }

            // 2. Find the Bus (Strict Active Check)
            $bus = Bus::where('status', 'Active')
                    ->orWhere('status', 'active')
                    ->first();

            // If no active bus is found, STOP immediately.
            if (!$bus) {
                return response()->json([
                    'message' => 'BUS INACTIVE',
                    'new_count' => 0
                ], 200);
            }

            // 3. Identification (Who is this?)
            $student = Student::where('card_uid', $uid)->first();

            // Block unknown cards
            if (!$student) {
                 return response()->json([
                    'message' => 'Unregistered',
                    'new_count' => $bus->current_passengers
                ], 200);
            }

            $identifier = $student->matric_no;
            $name = $student->name; // Get Student Name

            // 4. Check Logic: Are they already on the bus?
            $existingPassenger = Passenger::where('card_uid', $uid)->first();

            if ($existingPassenger) {
                // --- EXITING LOGIC ---
                $existingPassenger->delete();

                if ($bus->current_passengers > 0) {
                    $bus->decrement('current_passengers');
                }

                // SAVE LOG (EXITING)
                BusLog::create([
                    'card_uid' => $uid,
                    'matric_no' => $identifier,
                    'student_name' => $name,
                    'bus_id' => $bus->id,
                    'action' => 'EXITING'
                ]);

                $message = "BYE " . $identifier;
            } else {
                // --- BOARDING LOGIC ---
                if ($bus->current_passengers >= $bus->capacity) {
                     return response()->json([
                        'message' => 'BUS FULL',
                        'new_count' => $bus->current_passengers
                    ], 200);
                }

                Passenger::create(['card_uid' => $uid]);
                $bus->increment('current_passengers');

                // SAVE LOG (BOARDING)
                BusLog::create([
                    'card_uid' => $uid,
                    'matric_no' => $identifier,
                    'student_name' => $name,
                    'bus_id' => $bus->id,
                    'action' => 'BOARDING'
                ]);

                $message = "HI " . $identifier;
            }

            return response()->json([
                'message' => $message,
                'new_count' => $bus->current_passengers
            ], 200);

        } catch (\Exception $e) {
            // Catch Block handles errors
            Log::error("RFID Error: " . $e->getMessage());

            return response()->json([
                'message' => 'System Error',
                'new_count' => 0,
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function dailyReport(Request $request)
    {
        // Default to today, or use the date selected by the admin
        $date = $request->input('date', Carbon::today()->toDateString());

        // Fetch logs for that specific date
        $logs = BusLog::whereDate('created_at', $date)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Calculate Stats
        $totalBoarding = $logs->where('action', 'BOARDING')->count();
        // Count how many distinct matric numbers used the bus
        $uniqueStudents = $logs->unique('matric_no')->count();

        return view('admin.report', compact('logs', 'date', 'totalBoarding', 'uniqueStudents'));
    }

    // --- CRUD OPERATIONS (Admin) ---

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
            'model' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'route' => 'required|string|max:50',
            'status' => 'required|string',
            'driver_name' => 'required|string|max:100',
            'driver_contact' => 'required|string|max:20',
        ]);

        Bus::create($request->all());
        return redirect()->back()->with('success', 'Bus added successfully!');
    }

    public function edit(Bus $bus)
    {
        return response()->json($bus);
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
            'model' => 'required|string|max:100',
            'capacity' => 'required|integer',
            'route' => 'required|string|max:50',
            'status' => 'required|string',
            'driver_name' => 'required|string|max:100',
            'driver_contact' => 'required|string|max:20',
        ]);

        $bus->update($request->all());
        return redirect()->back()->with('success', 'Bus updated successfully!');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->back()->with('success', 'Bus deleted successfully!');
    }

    public function updateLocation(Request $request)
{
    // 1. Validate incoming data from ESP32
    $request->validate([
        'latitude' => 'required',
        'longitude' => 'required',
        // 'bus_id' => 'required' // Uncomment if you have multiple devices
    ]);

    // 2. Update the Bus Record (Assuming ID 1 for this prototype)
    $bus = Bus::find(1);
    if ($bus) {
        $bus->latitude = $request->latitude;
        $bus->longitude = $request->longitude;
        $bus->save();
        return response()->json(['status' => 'success'], 200);
    }

    return response()->json(['status' => 'bus_not_found'], 404);
}

public function getLocations()
{
    // Return all active buses to the Javascript Map
    return Bus::all();
}
}
