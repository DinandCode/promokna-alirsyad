<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Mail\RacepackConfirmationMail;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MasterParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Participant::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bib_name', 'like', "%{$search}%")
                    ->orWhere('bib', $search)
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('payment_status')) {
            if ($request->payment_status === 'no_payment') {
                $query->whereDoesntHave('payment');
            } elseif (in_array($request->payment_status, ['paid', 'pending', 'expired'])) {
                $query->whereHas('payment', function ($q) use ($request) {
                    $q->where('status', $request->payment_status);
                });
            }
        }

        $participants = $query->with('ticket')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($participants);
    }


    /**
     * Store a newly created participant in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:participants,email',
            'phone'      => 'required|string|max:20',
            'community'  => 'nullable|string|max:255',
            'gender'     => 'nullable|in:female,male',
            'nik'        => 'nullable|string|max:16',
            'birthplace' => 'nullable|string|max:255',
            'birthdate'  => 'nullable|date',
            'address'    => 'nullable|string',
            'city'       => 'nullable|string|max:255',
            'jersey_size' => 'nullable|in:XXS,XS,S,M,L,XL,2XL,3XL,4XL,5XL,6XL',
            'blood_type' => 'nullable|string|max:5',
            'medical_history' => 'nullable|string',
            'medical_note'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $bib = str_pad(Participant::count() + 1, 4, '0', STR_PAD_LEFT);

        $participant = Participant::create(array_merge($validated, ['bib' => $bib]));

        return response()->json(['message' => 'Participant created successfully', 'participant' => $participant]);
    }

    /**
     * Display the specified participant.
     */
    public function show(Participant $participant)
    {
        return response()->json($participant);
    }

    /**
     * Update the specified participant in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name'  => 'sometimes|required|string|max:255',
            'email'      => 'sometimes|required|email|unique:participants,email,' . $participant->id,
            'phone'      => 'sometimes|required|string|max:20',
            'community'  => 'nullable|string|max:255',
            'gender'     => 'nullable|in:female,male',
            'nik'        => 'nullable|string|max:16',
            'birthplace' => 'nullable|string|max:255',
            'birthdate'  => 'nullable|date',
            'address'    => 'nullable|string',
            'city'       => 'nullable|string|max:255',
            'jersey_size' => 'nullable',
            'blood_type' => 'nullable|string|max:5',
            'medical_history' => 'nullable|string',
            'medical_note'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participant->update($validator->validated());

        return response()->json(['message' => 'Participant updated successfully', 'participant' => $participant]);
    }

    public function updateRacepack(Request $request, Participant $participant)
    {
        $userId = request('user_id');
        $validator = Validator::make($request->all(), [
            'sendiri' => 'nullable',
            'taken_by' => 'nullable|string',
            'taken_phone' => 'nullable|string',
            'taken_relationship' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        if ($data['sendiri']) {
            $data['taken_by'] = 'Diambil Sendiri';
            $data['taken_phone'] = '-';
            $data['taken_relationship'] = '-';
        }

        $data['handled_by'] = $userId;
        $participant->update($data);

        Mail::to($participant->email)->queue(new RacepackConfirmationMail($participant));

        return response()->json(['message' => 'Participant updated sucessfully', 'participant' => $participant]);
    }

    /**
     * Remove the specified participant from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();
        return response()->json(['message' => 'Participant deleted successfully']);
    }
}
