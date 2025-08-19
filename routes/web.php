<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\MasterSettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Mail\ParticipantConfirmationMail;
use App\Models\MailLog;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/registration', [HomeController::class, 'register'])->name('user.register');
Route::post('/registration', [UserController::class, 'attemptRegister'])->name('user.attempt-register');

// Form pendaftaran GRATIS
Route::get('/registration/{ticket:id}', [HomeController::class, 'registerTicket'])->name('user.register-ticket');
Route::post('/registration', [UserController::class, 'attemptRegisterTicket'])->name('user.attempt-register-ticket');

Route::get('/register-success/{participant:id}', [UserController::class, 'registerSuccess'])->name('user.register-success');
Route::get('/pay/{participant:id}/payment/{payment:id}', [PaymentController::class, 'pay'])->withoutScopedBindings()->name('payment.pay');

Route::get('/peserta', [UserController::class, 'listUser'])->name('user.peserta');
Route::get('/peserta/{bib}/print-bib', [UserController::class, 'printBIB'])->name('user.print-bib');

Route::get('/faq', [HomeController::class, 'faq'])->name('home.faq');

Route::get('/sk', [HomeController::class, 'sk'])->name('home.sk');

Route::post('/send-message', [HomeController::class, 'sendMessage'])->name('home.send-message');

Route::get('/preview-bib', [HomeController::class, 'previewBib'])->name('home.preview-bib');

Route::get('/api/preview-bib', [AdminController::class, 'cookieGetFotoBIBValue']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin', [AdminController::class, 'peserta'])->name('admin.peserta');

    Route::get('/trator', [AdminController::class, 'administrator'])->name('admin.administrator')->middleware('can:is-superadmin');
    Route::get('/report', [AdminController::class, 'laporan'])->name('admin.laporan')->middleware(EnsureUserIsAdmin::class);
    Route::get('/setting', [AdminController::class, 'pengaturan'])->name('admin.pengaturan')->middleware(EnsureUserIsAdmin::class);

    Route::get('/pesan', [AdminController::class, 'pesan'])->name('admin.pesan')->middleware(EnsureUserIsAdmin::class);

    Route::post('/admin/settings/update', [MasterSettingController::class, 'update'])->name('admin.settings.update')->middleware(EnsureUserIsAdmin::class);
    Route::post('/admin/faqs/update', [MasterSettingController::class, 'updateFaq'])->name('admin.faqs.update')->middleware(EnsureUserIsAdmin::class);

    Route::get('/admin/laporan/export', [AdminController::class, 'exportPeserta'])->name('admin.laporan.export')->middleware(EnsureUserIsAdmin::class);
    Route::get('/admin/mails', [AdminController::class, 'mailLogs'])->name('admin.mails')->middleware(EnsureUserIsAdmin::class);
    Route::get('/admin/mails/{id}/resend', [AdminController::class, 'resendMail'])->name('admin.mails.resend')->middleware(EnsureUserIsAdmin::class);

    Route::resource('/admin/tiket', TicketController::class)->middleware(EnsureUserIsAdmin::class)->names('admin.tickets');

    Route::get('/admin/fotobib', [AdminController::class, 'showFotoBibForm'])->name('admin.fotobib.show');
    Route::post('/admin/fotobib', [AdminController::class, 'handleFotoBibForm'])->name('admin.fotobib.submit');
});

Route::get('/masuk', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::post('/masuk', [AdminController::class, 'handleLogin'])->name('handle-login');

Route::get('/peserta/login/{ticket:id}', [AuthController::class, 'showLoginForm'])->name('peserta.login');
Route::post('/peserta/login/{ticket:id}', [AuthController::class, 'login'])->name('peserta.login.submit');

Route::get('/preview-confirmation/{bib}', function ($bib) {
    $participant = Participant::where('bib', $bib)->first();
    if (!$participant) {
        return abort(404);
    }

    return view('emails.participant_confirmation', compact('participant'));
});

Route::get('/send-confirmation/{bib}', function ($bib) {
    $detail = DB::table('participant_detail')->where('bib', $bib)->first();
    $participant = Participant::where('email', $detail->email)->first();
    $log = MailLog::create([
        'to' => $detail->email ?? '-',
        'subject' => 'Promokna.id' . ' | Konfirmasi Kepesertaan Event',
        'content' => view('emails.participant_confirmation', [
            'detail' => $detail,
            'participant' => $participant
        ])->render(),
        'status' => 'pending',
    ]);
    Mail::to($detail->email)->send(new ParticipantConfirmationMail($detail, $participant));

    return view('emails.participant_confirmation', compact('detail', 'participant'));
});
