<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Services\HolidayService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }
    public function index(Request $request)
    {
        $country = $request->input('country', 'US'); // Default to US
        $year = $request->input('year', date('Y')); // Default to current year
        $holidays = $this->holidayService->fetchHolidays($country, $year);
        // Store holidays in the database if they don't exist
        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                [
                    'name' => $holiday['name'],
                    'date' => Carbon::parse($holiday['date']['iso'])->format('Y-m-d H:i:s'),
                    'type' => implode(", ", $holiday['type']),
                    'country' => $country
                ]
            );
        }

        $savedHolidays = Holiday::where('country', $country)->whereYear('date', $year)->get();

        return view('holidays.index', compact('savedHolidays', 'country', 'year'));
    }

    public function fetchHolidays($country_code, $year)
    {

        $holidays = $this->holidayService->fetchHolidays($country_code, $year);
        // Store holidays in the database if they don't exist
        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                [
                    'name' => $holiday['name'],
                    'date' => Carbon::parse($holiday['date']['iso'])->format('Y-m-d H:i:s'),
                    'type' => implode(", ", $holiday['type']),
                    'country' => $country_code
                ]
            );
        }

        $savedHolidays = Holiday::where('country', $country_code)->whereYear('date', $year)->get();

        // return view('holidays.index', compact('savedHolidays', 'country', 'year'));
    }

    public function showCalendar(Request $request)
    {
        $currentMonth = $request->query('month', now()->month);
        $currentYear = now()->year;
        $currentCountry = $request->query('country', 'US'); // Default to United States

        // Check if the holiday data for the selected country and year exists
        $holidaysForYear = Holiday::where('country', $currentCountry)
                                ->whereYear('date', $currentYear)
                                ->count();

        // If no data exists for the entire year, fetch from the API
        if ($holidaysForYear === 0) {
            $this->fetchHolidays($currentCountry, $currentYear);
        }

        // Fetch holidays for the selected month
        $holidays = Holiday::where('country', $currentCountry)
                        ->whereYear('date', $currentYear)
                        ->whereMonth('date', $currentMonth)
                        ->get();

        return view('holidays.calendar', compact('holidays', 'currentMonth', 'currentCountry', 'currentYear'));
    }

    public function addHoliday(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            'type' => 'required',
        ]);

        $holiday = Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'country' => $request->country, // assuming the country is passed in form or controller
        ]);
        // dd($holiday);
        return redirect()->back()->with('success', 'Holiday added successfully!');
    }

    // Edit Holiday
    public function editHoliday(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:holidays,id',
            'name' => 'required',
            'type' => 'required',
        ]);

        $holiday = Holiday::find($request->id);
        $holiday->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Holiday updated successfully!');
    }

    // Delete Holiday
    public function deleteHoliday(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:holidays,id',
        ]);

        Holiday::destroy($request->id);

        return redirect()->back()->with('success', 'Holiday deleted successfully!');
    }
}
