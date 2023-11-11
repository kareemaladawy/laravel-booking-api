<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Http\Requests\Bookings\UpdateBookingRequest;
use App\Http\Resources\Bookings\BookingResource;
use App\Jobs\UpdatePropertyRatingJob;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('apartment.property')
            ->withTrashed()
            ->orderBy('start_date')
            ->get();

        return BookingResource::collection($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = auth()->user()->bookings()->create($request->validated());

        return BookingResource::make($booking);
    }

    public function show(Booking $booking)
    {
        return BookingResource::make($booking);
    }

    public function update(Booking $booking, UpdateBookingRequest $request)
    {
        $booking->update($request->validated());

        dispatch(new UpdatePropertyRatingJob($booking));

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    public function cancel(Booking $booking)
    {
        $booking->cancel();

        return response()->json([
            'message' => 'cancelled.'
        ], 200);
    }
}
