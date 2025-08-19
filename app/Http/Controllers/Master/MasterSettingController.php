<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\RundownContent;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MasterSettingController extends Controller
{
    public function update(Request $request)
    {
        // Handle rundown
        if ($request->filled('rundowns')) {
            $rundowns = json_decode($request->input('rundowns'), true);

            RundownContent::truncate();

            foreach ($rundowns as $item) {
                if (!empty($item['judul']) && !empty($item['tanggal'])) {
                    RundownContent::create([
                        'title' => $item['judul'],
                        'occasion_date' => $item['tanggal'],
                        'description' => $item['deskripsi'] ?? '',
                    ]);
                }
            }
        }

        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['rundowns'])) continue;

            if ($request->hasFile($key)) {
                $oldLogoPath = Setting::get($key);

                $relativePath = str_replace('/storage/', '', parse_url($oldLogoPath, PHP_URL_PATH));

                if ($relativePath && Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }

                $path = $request->file($key)->store('logo', ['disk' => 'public']);
                $url = Storage::url($path);
                Setting::updateOrCreate(['key' => $key], ['value' => $url]);

                continue;
            }

            Setting::updateOrCreate(['key' => $key], [
                'value' => ($value != null ? $value : '')
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function updateFaq(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['faqs'])) continue;
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value != null ? $value : ''
            ]);
        }

        // Update kolom FAQs
        if ($request->has('faqs')) {
            $faqs = json_decode($request->input('faqs'), true);

            Faq::truncate();

            foreach ($faqs as $item) {
                Faq::create([
                    'question' => $item['pertanyaan'],
                    'answer' => $item['jawaban']
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
