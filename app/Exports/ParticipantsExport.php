<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ParticipantsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Participant::query()->with(['payment', 'handledBy', 'ticket']);

        // Filter pencarian
        if ($search = $this->request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bib_name', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('bib', 'like', "%$search%");
            });
        }

        // Filter jenis kelamin
        if ($racepack = $this->request->input('racepack')) {
            if ($racepack == 'diambil') $query->whereNot('taken_by', null);
            else if ($racepack == 'belum') $query->where('taken_by', null);
        }

        // Filter status pembayaran
        if ($this->request->filled('payment_status')) {
            if ($this->request->payment_status === 'none') {
                $query->whereDoesntHave('payment');
            } else {
                $query->whereHas('payment', function ($q) {
                    $q->where('status', $this->request->payment_status);
                });
            }
        }

        return $query->get()->map(function ($participant) {
            return [
                'bib'            => $participant->bib,
                'category'       => $participant->ticket->name,
                'bib_name'     => $participant->bib_name,
                'full_name'      => $participant->full_name,
                'email'          => $participant->email,
                'phone'          => strval("'" . $participant->phone),
                'gender'         => $participant->gender,
                'community'      => $participant->community,
                'nik'            => strval("'" . $participant->nik),
                'birthplace'     => $participant->birthplace,
                'birthdate'      => $participant->birthdate,
                'address'        => $participant->address,
                'city'           => $participant->city,
                'jersey_size'    => strtoupper($participant->jersey_size),
                'blood_type'     => $participant->blood_type,
                'medical_history' => $participant->medical_history,
                'medical_note'   => $participant->medical_note,
                'payment_status' => $participant->payment_status,
                'jersey'         => $participant->payment_status != null ? 'ya' : 'tidak',
                'Sudah Diambil'     => $participant->pack_taken ? 'Sudah' : 'Belum',
                'Diambil Oleh'     => $participant->taken_by,
                'Telepon Pengambil'     => $participant->taken_phone,
                'Hubungan dengan Pemilik'     => $participant->taken_relationship,
                'Dilayani Oleh'      => $participant->handledBy ? $participant->handledBy->first_name . ' ' . $participant->handledBy->last_name : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'BIB',
            'Category',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Gender',
            'Community/Tahun Lulus',
            'NIK',
            'Birthplace',
            'Birthdate',
            'Address',
            'City/Pendidikan Terakhir',
            'Jersey Size',
            'Blood Type',
            'Medical History',
            'Medical Note',
            'Status',
            'Jersey',
            'Sudah Diambil',
            'Diambil Oleh',
            'Telepon Pengambil',
            'Hubungan dengan Pemilik',
            'Dilayani Oleh'
        ];
    }
}
