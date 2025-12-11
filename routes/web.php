<?php

use App\Http\Controllers\GlobalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Tarif\TarifTindakanController;
use App\Http\Controllers\Tarif\SKTarifController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\User\RollsController;

use App\Http\Controllers\Sdm\SpdsController;

use App\Http\Controllers\MasterData\PasienController;
use App\Http\Controllers\MasterData\Icd9Controller;
use App\Http\Controllers\MasterData\CoaController;
use App\Http\Controllers\MasterData\Icd10Controller;
use App\Http\Controllers\MasterData\Jadwal_dokterController;
use App\Http\Controllers\MasterData\SpesialisController;
use App\Http\Controllers\MasterData\PenjaminController;
use App\Http\Controllers\MasterData\PetugasController;
use App\Http\Controllers\MasterData\Poli_obatController;
use App\Http\Controllers\MasterData\Poli_tindakanController;
use App\Http\Controllers\MasterData\PoliController;
use App\Http\Controllers\MasterData\AsetController;
use App\Http\Controllers\MasterData\KalibrasiController;
use App\Http\Controllers\MasterData\LokasiController;
use App\Http\Controllers\MasterData\KondisiAsetController;
use App\Http\Controllers\Tarif\HargaTindakanController;
use App\Http\Controllers\MasterData\MutasiController;
use App\http\Controllers\MasterData\KelompokAsetController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\Admin\HelpDeskController as AdminHelpDeskController;
use App\Http\Controllers\User\HelpDeskController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Sdm\PegawaiController;

// Login/Logout Route Middleware
Route::group(['middleware' => 'login.check'], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('admin.login');
    Route::post('/process-login', [LoginController::class, 'login'])->name('admin.login-process');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// ADMIN HELPDESK
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('help-desk', [AdminHelpDeskController::class, 'index'])->name('admin.helpdesk');
    Route::get('help-desk/views', [AdminHelpDeskController::class, 'views'])->name('admin.helpdesk-views');
    Route::get('help-desk/edit/{helpDesk}', [AdminHelpDeskController::class, 'edit'])->name('admin.helpdesk-edit');
    Route::put('help-desk/{helpDesk}', [AdminHelpDeskController::class, 'update'])->name('admin.helpdesk-update');
    Route::post('help-desk/update-status/{helpDesk}', [AdminHelpDeskController::class, 'updateStatus'])->name('admin.helpdesk-update-status');
    Route::delete('help-desk/{helpDesk}', [AdminHelpDeskController::class, 'destroy'])->name('admin.helpdesk-destroy');

    Route::get('helpdesk/info/{id}', [AdminHelpDeskController::class, 'getHelpdeskInfo'])->name('admin.helpdesk-info');
    Route::get('chat/{helpdeskId}', [AdminChatController::class, 'index'])->name('admin.chat');
    Route::post('chat/{helpdeskId}/send', [AdminChatController::class, 'send'])->name('admin.chat-send');
});

// USER HELPDESK
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('help-desk', [HelpDeskController::class, 'index'])->name('user.helpdesk');
    Route::post('help-desk/add', [HelpDeskController::class, 'store'])->name('user.helpdesk-store');
    Route::get('help-desk/views', [HelpDeskController::class, 'views'])->name('user.helpdesk-views');
    Route::delete('help-desk/{helpDesk}', [HelpDeskController::class, 'destroy'])->name('user.helpdesk-delete');

    // USER CHAT
    Route::get('chat/{helpdeskId}', [ChatController::class, 'index'])->name('user.chat');
    Route::post('chat/{helpdeskId}/send', [ChatController::class, 'send'])->name('user.chat-send');
});


// SDM
Route::get('sdm', [PegawaiController::class, 'index'])->name('pegawai');
Route::get('sdm/views', [PegawaiController::class, 'views'])->name('pegawai-view');
Route::PUT('sdm/update/{id}', [PegawaiController::class, 'update'])->name('pegawai-update');
Route::POST('sdm/users/add', [PegawaiController::class, 'store'])->name('pegawai-store');
Route::get('sdm/destroy/{id}', [PegawaiController::class, 'destroy'])->name('pegawai-delete');
Route::get('sdm/updateStatus/{id}', [PegawaiController::class, 'updateStatus'])->name('pegawai.update-status');


// Logged In Route Middleware
Route::group(['middleware' => 'loggedin'], function () {
    Route::get('/', function () {
        return redirect()->route('home');
    })->name('/');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('master-data')->group(function () {
        // ICD-9
        Route::get('/icd-9', [Icd9Controller::class, 'index'])->name('master-data.icd-9');
        Route::get('/icd-9/view', [Icd9Controller::class, 'views'])->name('master-data.icd-9.view');
        Route::post('/icd-9/store', [Icd9Controller::class, 'store'])->name('master-data.icd-9.create');
        Route::post('/icd-9/update-status/{id}', [Icd9Controller::class, 'updateStatus'])->name('master-data.icd-9.update-status');
        Route::put('/icd-9/update/{id}', [Icd9Controller::class, 'update'])->name('master-data.icd-9.update');
        Route::delete('/icd-9/delete/{id}', [Icd9Controller::class, 'destroy'])->name('master-data.icd-9.delete');
        // ICD-10
        Route::get('/icd-10', [Icd10Controller::class, 'index'])->name('master-data.icd-10');
        Route::get('/icd-10/view', [Icd10Controller::class, 'views'])->name('master-data.icd-10.view');
        Route::post('/icd-10/store', [Icd10Controller::class, 'store'])->name('master-data.icd-10.create');
        Route::post('/icd-10/update-status/{id}', [Icd10Controller::class, 'updateStatus'])->name('master-data.icd-10.update-status');
        Route::put('/icd-10/update/{id}', [Icd10Controller::class, 'update'])->name('master-data.icd-10.update');
        Route::delete('/icd-10/delete/{id}', [Icd10Controller::class, 'destroy'])->name('master-data.icd-10.delete');
        // COA
        Route::get('/coa', [CoaController::class, 'index'])->name('master-data.coa');
        Route::get('/coa/view', [CoaController::class, 'views'])->name('master-data.coa.view');
        Route::post('/coa/store', [CoaController::class, 'store'])->name('master-data.coa.create');
        Route::post('/coa/update-status/{id}', [CoaController::class, 'updateStatus'])->name('master-data.coa.update-status');
        Route::put('/coa/update/{id}', [CoaController::class, 'update'])->name('master-data.coa.update');
        Route::delete('/coa/delete/{id}', [CoaController::class, 'destroy'])->name('master-data.coa.delete');
        // SPESIALIS
        Route::get('/spesialis', [SpesialisController::class, 'index'])->name('master-data.spesialis');
        Route::get('/spesialis/view', [SpesialisController::class, 'views'])->name('master-data.spesialis.view');
        Route::post('/spesialis/store', [SpesialisController::class, 'store'])->name('master-data.spesialis.create');
        Route::post('/spesialis/update-status/{id}', [SpesialisController::class, 'updateStatus'])->name('master-data.spesialis.update-status');
        Route::put('/spesialis/update/{id}', [SpesialisController::class, 'update'])->name('master-data.spesialis.update');
        Route::delete('/spesialis/delete/{id}', [SpesialisController::class, 'destroy'])->name('master-data.spesialis.delete');
        // POLIKLINIK
        Route::get('/poli', [PoliController::class, 'index'])->name('master-data.poli');
        Route::get('/poli/view', [PoliController::class, 'views'])->name('master-data.poli.view');
        Route::post('/poli/store', [PoliController::class, 'store'])->name('master-data.poli.create');
        Route::put('/poli/update/{id}', [PoliController::class, 'update'])->name('master-data.poli.update');
        Route::delete('/poli/delete/{id}', [PoliController::class, 'destroy'])->name('master-data.poli.delete');
        //MAPPING TINDAKAN POLI
        Route::get('/tindakan-poli', [Poli_tindakanController::class, 'index'])->name('master-data.tindakan-poli');
        Route::get('/tindakan-poli/view', [Poli_tindakanController::class, 'views'])->name('master-data.tindakan-poli.view');
        Route::post('/tindakan-poli/store', [Poli_tindakanController::class, 'store'])->name('master-data.tindakan-poli.create');
        Route::post('/tindakan-poli/update-status/{id}', [Poli_tindakanController::class, 'updateStatus'])->name('master-data.tindakan-poli.update-status');
        Route::put('/tindakan-poli/update/{id}', [Poli_tindakanController::class, 'update'])->name('master-data.tindakan-poli.update');
        Route::delete('/tindakan-poli/delete/{id}', [Poli_tindakanController::class, 'destroy'])->name('master-data.tindakan-poli.delete');
        //MAPPING OBAT POLI
        Route::get('/obat-poli', [Poli_obatController::class, 'index'])->name('master-data.obat-poli');
        Route::get('/obat-poli/view', [Poli_obatController::class, 'views'])->name('master-data.obat-poli.view');
        Route::post('/obat-poli/store', [Poli_obatController::class, 'store'])->name('master-data.obat-poli.create');
        Route::post('/obat-poli/update-status/{id}', [Poli_obatController::class, 'updateStatus'])->name('master-data.obat-poli.update-status');
        Route::put('/obat-poli/update/{id}', [Poli_obatController::class, 'update'])->name('master-data.obat-poli.update');
        Route::delete('/obat-poli/delete/{id2}', [Poli_obatController::class, 'destroy'])->name('master-data.obat-poli.delete');
        // PENJAMIN
        Route::get('/penjamin', [PenjaminController::class, 'index'])->name('master-data.penjamin');
        Route::get('/penjamin/view', [PenjaminController::class, 'views'])->name('master-data.penjamin.view');
        Route::get('/penjamin/get-detail-diskon/{id}', [PenjaminController::class, 'get_detail_discont'])->name('master-data.penjamin.detail-diskon');
        Route::post('/penjamin/store', [PenjaminController::class, 'store'])->name('master-data.penjamin.create');
        Route::post('/penjamin/update-status/{id}', [PenjaminController::class, 'updateStatus'])->name('master-data.penjamin.update-status');
        Route::put('/penjamin/update/{id}', [PenjaminController::class, 'update'])->name('master-data.penjamin.update');
        Route::delete('/penjamin/delete/{id}', [PenjaminController::class, 'destroy'])->name('master-data.penjamin.delete');
        Route::get('/select-coa', [PenjaminController::class, 'select'])->name('master-data.penjamin.select');
        Route::get('/select-tarif', [PenjaminController::class, 'select_tarif'])->name('master-data.penjamin.select_tarif');
        // PETUGAS
        Route::get('/petugas', [PetugasController::class, 'index'])->name('master-data.petugas');
        Route::get('/petugas/view', [PetugasController::class, 'views'])->name('master-data.petugas.view');
        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('master-data.petugas.create');
        Route::post('/petugas/update/{id}', [PetugasController::class, 'update'])->name('master-data.petugas.update');
        Route::put('/petugas/update_signature/{id}', [PetugasController::class, 'update_signature'])->name('master-data.petugas.update_signature');
        Route::delete('/petugas/delete/{id}', [PetugasController::class, 'destroy'])->name('master-data.petugas.delete');
        // JADWAL
        Route::get('/jadwal-dokter', [Jadwal_dokterController::class, 'index'])->name('master-data.jadwal-dokter');
        Route::get('/jadwal-dokter/view', [Jadwal_dokterController::class, 'views'])->name('master-data.jadwal-dokter.view');
        Route::post('/jadwal-dokter/store', [Jadwal_dokterController::class, 'store'])->name('master-data.jadwal-dokter.create');
        Route::post('/jadwal-dokter/update-status/{id}', [Jadwal_dokterController::class, 'updateStatus'])->name('master-data.jadwal-dokter.update-status');
        Route::put('/jadwal-dokter/update/{id}', [Jadwal_dokterController::class, 'update'])->name('master-data.jadwal-dokter.update');
        Route::delete('/jadwal-dokter/delete/{id}', [Jadwal_dokterController::class, 'destroy'])->name('master-data.jadwal-dokter.delete');
        Route::get('/jadwal-poli', [Jadwal_dokterController::class, 'select'])->name('master-data.poli.select');
        Route::get('/jadwal-petugas', [Jadwal_dokterController::class, 'select_petugas'])->name('master-data.petugas.select');
        // Tarif Tindakan
        Route::get('/tarif-tindakan', [TarifTindakanController::class, 'index'])->name('master-data.tarif-tindakan');
        Route::get('/tarif-tindakan/form-tarif-baru', [TarifTindakanController::class, 'form_tarif'])->name('master-data.tarif-tindakan.form');
        // PASIEN
        Route::get('/pasien', [PasienController::class, 'index'])->name('master-data.pasien');
        Route::get('/pasien/view', [PasienController::class, 'views'])->name('master-data.pasien.view');
        Route::post('/pasien/store', [PasienController::class, 'store'])->name('master-data.pasien.create');
        Route::post('/pasien/update-status/{id}', [PasienController::class, 'updateStatus'])->name('master-data.pasien.update-status');
        Route::put('/pasien/update/{id}', [PasienController::class, 'update'])->name('master-data.pasien.update');
        Route::delete('/pasien/delete/{id}', [PasienController::class, 'destroy'])->name('master-data.pasien.delete');

        // Asset
        Route::get('/aset', [AsetController::class, 'index'])->name('master-data.aset');
        Route::get('/aset/view', [AsetController::class, 'views'])->name('master-data.aset.view');
        Route::post('/aset/store', [AsetController::class, 'store'])->name('master-data.aset.create');
        Route::put('/aset/update/{id}', [AsetController::class, 'update'])->name('master-data.aset.update');
        Route::delete('/aset/delete/{id}', [AsetController::class, 'destroy'])->name('master-data.aset.delete');
        Route::post('/aset/update-status/{id}', [AsetController::class, 'updateStatus'])->name('master-data.aset.update-status');
        Route::get('/aset/view_mutasi', [AsetController::class, 'views_mutasi'])->name('master-data.aset.view_mutasi');
        Route::get('/aset/print/{id}', [AsetController::class, 'print'])->name('master-data.aset.print');
        Route::get('/aset/print-all', [AsetController::class, 'printAll'])->name('master-data.aset.print-all');

        // Lokasi
        Route::get('/lokasi', [LokasiController::class, 'index'])->name('master-data.lokasi');
        Route::get('/lokasi/view', [LokasiController::class, 'views'])->name('master-data.lokasi.view');
        Route::post('/lokasi/store', [LokasiController::class, 'store'])->name('master-data.lokasi.create');
        Route::put('/lokasi/update/{id}', [LokasiController::class, 'update'])->name('master-data.lokasi.update');
        Route::delete('/lokasi/delete/{id}', [LokasiController::class, 'destroy'])->name('master-data.lokasi.delete');
        Route::post('/lokasi/update-status/{id}', [LokasiController::class, 'updateStatus'])->name('master-data.lokasi.update-status');

        // Kalibrasi
        Route::get('/kalibrasi', [KalibrasiController::class, 'index'])->name('master-data.kalibrasi');
        Route::get('/kalibrasi/view', [KalibrasiController::class, 'views'])->name('master-data.kalibrasi.view');
        Route::post('/kalibrasi/store', [KalibrasiController::class, 'store'])->name('master-data.kalibrasi.create');
        Route::put('/kalibrasi/update/{id}', [KalibrasiController::class, 'update'])->name('master-data.kalibrasi.update');
        Route::delete('/kalibrasi/delete/{id}', [KalibrasiController::class, 'destroy'])->name('master-data.kalibrasi.delete');
        Route::post('/kalibrasi/update-status/{id}', [KalibrasiController::class, 'updateStatus'])->name('master-data.kalibrasi.update-status');

        // Kondisi Asset
        Route::get('/kondisi-aset', [KondisiAsetController::class, 'index'])->name('master-data.kondisi-aset');
        Route::get('/kondisi-aset/view', [KondisiAsetController::class, 'views'])->name('master-data.kondisi-aset.view');
        Route::post('/kondisi-aset/store', [KondisiAsetController::class, 'store'])->name('master-data.kondisi-aset.create');
        Route::put('/kondisi-aset/update/{id}', [KondisiAsetController::class, 'update'])->name('master-data.kondisi-aset.update');
        Route::delete('/kondisi-aset/delete/{id}', [KondisiAsetController::class, 'destroy'])->name('master-data.kondisi-aset.delete');
        Route::post('/kondisi-aset/update-status/{id}', [KondisiAsetController::class, 'updateStatus'])->name('master-data.kondisi-aset.update-status');

        // Mutasi
        Route::get('/mutasi', [MutasiController::class, 'index'])->name('master-data.mutasi');
        Route::get('/mutasi/view', [MutasiController::class, 'views'])->name('master-data.mutasi.view');
        Route::post('/mutasi/store', [MutasiController::class, 'store'])->name('master-data.mutasi.create');
        Route::put('/mutasi/update/{id}', [MutasiController::class, 'update'])->name('master-data.mutasi.update');
        Route::delete('/mutasi/delete/{id}', [MutasiController::class, 'destroy'])->name('master-data.mutasi.delete');
        Route::post('/mutasi/update-status/{id}', [MutasiController::class, 'updateStatus'])->name('master-data.mutasi.update-status');
        Route::get('/mutasi/detail/{id}', [MutasiController::class, 'getDetailAset'])->name('master-data.mutasi.detail');

        // Kelompok Asset
        Route::get('/kelompok-aset', [KelompokAsetController::class, 'index'])->name('master-data.kelompok-aset');
        Route::get('/kelompok-aset/view', [KelompokAsetController::class, 'views'])->name('master-data.kelompok-aset.view');
        Route::post('/kelompok-aset/store', [KelompokAsetController::class, 'store'])->name('master-data.kelompok-aset.create');
        Route::put('/kelompok-aset/update/{id}', [KelompokAsetController::class, 'update'])->name('master-data.kelompok-aset.update');
        Route::delete('/kelompok-aset/delete/{id}', [KelompokAsetController::class, 'destroy'])->name('master-data.kelompok-aset.delete');
        Route::post('/kelompok-aset/update-status/{id}', [KelompokAsetController::class, 'updateStatus'])->name('master-data.kelompok-aset.update-status');

        // Kelompok customer
        Route::get('/customer', [CustomerController::class, 'index'])->name('master-data.customer');
        Route::get('/customer/view', [CustomerController::class, 'views'])->name('master-data.customer.view');
        Route::post('/customer/store', [CustomerController::class, 'store'])->name('master-data.customer.create');
        Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('master-data.customer.update');
        Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('master-data.customer.delete');
        Route::post('/customer/update-status/{id}', [CustomerController::class, 'updateStatus'])->name('master-data.customer.update-status');
    });

    // User Management
    Route::prefix('user')->group(function () {
        // User
        Route::get('/user', [UsersController::class, 'index'])->name('user.user');
        Route::get('/user/view', [UsersController::class, 'views'])->name('user.user.view');
        Route::post('/user/store', [UsersController::class, 'store'])->name('user.user.create');
        Route::put('/user/update/{id}', [UsersController::class, 'update'])->name('user.user.update');
        Route::delete('/user/delete/{id}', [UsersController::class, 'destroy'])->name('user.user.delete');
        Route::post('/user/update-status/{id}', [UsersController::class, 'updateStatus'])->name('user.user.update-status');

        //Rolls
        Route::get('/roll', [RollsController::class, 'index'])->name('user.roll');
        Route::get('/roll/view', [RollsController::class, 'views'])->name('user.roll.view');
        Route::post('/roll/store', [RollsController::class, 'store'])->name('user.roll.create');
        Route::put('/roll/update/{id}', [RollsController::class, 'update'])->name('user.roll.update');
        Route::put('/roll/update-menu/{id}', [RollsController::class, 'updateMenu'])->name('user.roll.update-menu');
        Route::delete('/roll/delete/{id}', [RollsController::class, 'destroy'])->name('user.roll.delete');
        Route::post('/roll/update-status/{id}', [RollsController::class, 'updateStatus'])->name('user.roll.update-status');
    });

    // SDM
    Route::prefix('sdm')->group(function () {
        // SPD
        Route::get('/spd', [SpdsController::class, 'index'])->name('sdm.spd');
        Route::get('/spd/view', [SpdsController::class, 'views'])->name('sdm.spd.view');
        Route::post('/spd/store', [SpdsController::class, 'store'])->name('sdm.spd.create');
        Route::put('/spd/update/{id}', [SpdsController::class, 'update'])->name('sdm.spd.update');
        Route::delete('/spd/delete/{id}', [SpdsController::class, 'destroy'])->name('sdm.spd.delete');
        Route::post('/spd/update-status/{id}', [SpdsController::class, 'updateStatus'])->name('sdm.spd.update-status');

    });

    // Tarif
    Route::prefix('tarif')->group(function () {
        // SK Tarif
        Route::get('/sk-tarif', [SKTarifController::class, 'index'])->name('tarif.sk-tarif.index');
        Route::get('/sk-tarif/view', [SKTarifController::class, 'views'])->name('tarif.sk-tarif.view');
        Route::post('/sk-tarif/store', [SKTarifController::class, 'store'])->name('tarif.sk-tarif.create');
        Route::post('/sk-tarif/update-status/{id}', [SKTarifController::class, 'updateStatus'])->name('tarif.sk-tarif.update-status');
        Route::put('/sk-tarif/update/{id}', [SKTarifController::class, 'update'])->name('tarif.sk-tarif.update');
        Route::delete('/sk-tarif/delete/{id}', [SKTarifController::class, 'destroy'])->name('tarif.sk-tarif.delete');

        // Tindakan
        Route::get('/index', [TarifTindakanController::class, 'index'])->name('tarif.tindakan.index');
        Route::get('/tarif-tindakan/view', [TarifTindakanController::class, 'views'])->name('tarif.tindakan.view');
        Route::post('/tarif-tindakan/store', [TarifTindakanController::class, 'store'])->name('tarif.tindakan.create');
        Route::post('/tarif/update-status/{id}', [TarifTindakanController::class, 'updateStatus'])->name('tarif.tarif.update-status');
        Route::put('/tarif-tindakan/update/{id}', [TarifTindakanController::class, 'update'])->name('tarif.tindakan.update');
        Route::delete('/tarif-tindakan/delete/{id}', [TarifTindakanController::class, 'destroy'])->name('tarif.tindakan.delete');

        // Harga Tindakan
        Route::get('/index-harga', [HargaTindakanController::class, 'index'])->name('tarif.harga.index');
        Route::get('/harga-tindakan/view', [HargaTindakanController::class, 'views'])->name('tarif.harga.view');
        Route::post('/harga-tindakan/store', [HargaTindakanController::class, 'store'])->name('tarif.harga.create');
        Route::put('/harga-tindakan/update//{id}', [HargaTindakanController::class, 'update'])->name('tarif.harga.update');
        Route::delete('/harga-tindakan/delete/{id}', [HargaTindakanController::class, 'destroy'])->name('tarif.harga.delete');
    });

    // Router Controller Global
    Route::prefix('global-controller')->group(function () {
        // Spesialis
        Route::get('/get-select-spesialis', [GlobalController::class, 'optionsSelectSpesialis'])->name('get-select-spesialis');
        // Petugas
        Route::get('/get-select-petugas', [GlobalController::class, 'optionsSelectPetugas'])->name('get-select-petugas');
        // Poli
        Route::get('/get-select-poli', [GlobalController::class, 'optionsSelectPoli'])->name('get-select-poli');
        // COA
        Route::get('/get-select-coa', [GlobalController::class, 'optionsSelectCoa'])->name('get-select-coa');
        // Tindakan
        Route::get('/get-select-tindakan', [GlobalController::class, 'optionsSelectTindakan'])->name('get-select-tindakan');
        // Tarif Tindakan
        Route::get('/get-select-tarif-tindakan', [GlobalController::class, 'tarifTindakan'])->name('get-select-tarif-tindakan');
        // Generate Kode
        Route::get('/generate-kode-tarif-tindakan/{id}', [GlobalController::class, 'generateKodeTarifTindakan'])->name('generate-kode-tarif-tindakan');
        // Update Status
        Route::post('/update-status/{id}', [GlobalController::class, 'updateStatus'])->name('get-select-update-status');


        // Vendor
        Route::get('/get-select-roll', [GlobalController::class, 'optionsSelectRoll'])->name('get-select-roll');
        // Aset
        Route::get('/get-select-aset', [GlobalController::class, 'optionsSelectAset'])->name('get-select-aset');
        // lokasi
        Route::get('/get-select-lokasi', [GlobalController::class, 'optionsSelectLokasi'])->name('get-select-lokasi');
        // kondisi aset
        Route::get('/get-select-kondisi-aset', [GlobalController::class, 'optionsSelectKondisiAset'])->name('get-select-kondisi-aset');
        // Kelompok aset
        Route::get('/get-select-kelompok-aset', [GlobalController::class, 'optionsSelectKelompokAset'])->name('get-select-kelompok-aset');
        // Vendor
        Route::get('/get-select-vendor', [GlobalController::class, 'optionsSelectVendor'])->name('get-select-vendor');
    });
});








// Route::group(['middleware' => 'login.check'], function () {
//     // Dashboard




Route::prefix('dashboard')->group(function () {
    Route::view('index', 'dashboard.index')->name('index');
    Route::view('dashboard-02', 'dashboard.dashboard-02')->name('dashboard-02');
    Route::view('dashboard-03', 'dashboard.dashboard-03')->name('dashboard-03');
    Route::view('dashboard-04', 'dashboard.dashboard-04')->name('dashboard-04');
    Route::view('dashboard-05', 'dashboard.dashboard-05')->name('dashboard-05');
});

//     // Users
// });






//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');



Route::prefix('widgets')->group(function () {
    Route::view('general-widget', 'widgets.general-widget')->name('general-widget');
    Route::view('chart-widget', 'widgets.chart-widget')->name('chart-widget');
});

Route::prefix('page-layouts')->group(function () {
    Route::view('box-layout', 'page-layout.box-layout')->name('box-layout');
    Route::view('layout-rtl', 'page-layout.layout-rtl')->name('layout-rtl');
    Route::view('layout-dark', 'page-layout.layout-dark')->name('layout-dark');
    Route::view('hide-on-scroll', 'page-layout.hide-on-scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'page-layout.footer-light')->name('footer-light');
    Route::view('footer-dark', 'page-layout.footer-dark')->name('footer-dark');
    Route::view('footer-fixed', 'page-layout.footer-fixed')->name('footer-fixed');
});

Route::prefix('project')->group(function () {
    Route::view('projects', 'project.projects')->name('projects');
    Route::view('projectcreate', 'project.projectcreate')->name('projectcreate');
});

Route::view('file-manager', 'file-manager')->name('file-manager');
Route::view('kanban', 'kanban')->name('kanban');

Route::prefix('ecommerce')->group(function () {
    Route::view('product', 'apps.product')->name('product');
    Route::view('page-product', 'apps.product-page')->name('product-page');
    Route::view('list-products', 'apps.list-products')->name('list-products');
    Route::view('payment-details', 'apps.payment-details')->name('payment-details');
    Route::view('order-history', 'apps.order-history')->name('order-history');
    Route::view('invoice-template', 'apps.invoice-template')->name('invoice-template');
    Route::view('cart', 'apps.cart')->name('cart');
    Route::view('list-wish', 'apps.list-wish')->name('list-wish');
    Route::view('checkout', 'apps.checkout')->name('checkout');
    Route::view('pricing', 'apps.pricing')->name('pricing');
});

Route::prefix('email')->group(function () {
    Route::view('email-application', 'apps.email-application')->name('email-application');
    Route::view('email-compose', 'apps.email-compose')->name('email-compose');
});

Route::prefix('chat')->group(function () {
    Route::view('chat', 'apps.chat')->name('chat');
    Route::view('video-chat', 'apps.video-chat')->name('chat-video');
});

Route::prefix('users')->group(function () {
    Route::view('user-profile', 'apps.user-profile')->name('user-profile');
    Route::view('edit-profile', 'apps.edit-profile')->name('edit-profile');
    Route::view('user-cards', 'apps.user-cards')->name('user-cards');
});


Route::view('bookmark', 'apps.bookmark')->name('bookmark');
Route::view('contacts', 'apps.contacts')->name('contacts');
Route::view('task', 'apps.task')->name('task');
Route::view('calendar-basic', 'apps.calendar-basic')->name('calendar-basic');
Route::view('social-app', 'apps.social-app')->name('social-app');
Route::view('to-do', 'apps.to-do')->name('to-do');
Route::view('search', 'apps.search')->name('search');

Route::prefix('ui-kits')->group(function () {
    Route::view('state-color', 'ui-kits.state-color')->name('state-color');
    Route::view('typography', 'ui-kits.typography')->name('typography');
    Route::view('avatars', 'ui-kits.avatars')->name('avatars');
    Route::view('helper-classes', 'ui-kits.helper-classes')->name('helper-classes');
    Route::view('grid', 'ui-kits.grid')->name('grid');
    Route::view('tag-pills', 'ui-kits.tag-pills')->name('tag-pills');
    Route::view('progress-bar', 'ui-kits.progress-bar')->name('progress-bar');
    Route::view('modal', 'ui-kits.modal')->name('modal');
    Route::view('alert', 'ui-kits.alert')->name('alert');
    Route::view('popover', 'ui-kits.popover')->name('popover');
    Route::view('tooltip', 'ui-kits.tooltip')->name('tooltip');
    Route::view('loader', 'ui-kits.loader')->name('loader');
    Route::view('dropdown', 'ui-kits.dropdown')->name('dropdown');
    Route::view('accordion', 'ui-kits.accordion')->name('accordion');
    Route::view('tab-bootstrap', 'ui-kits.tab-bootstrap')->name('tab-bootstrap');
    Route::view('tab-material', 'ui-kits.tab-material')->name('tab-material');
    Route::view('box-shadow', 'ui-kits.box-shadow')->name('box-shadow');
    Route::view('list', 'ui-kits.list')->name('list');
});

Route::prefix('bonus-ui')->group(function () {
    Route::view('scrollable', 'bonus-ui.scrollable')->name('scrollable');
    Route::view('tree', 'bonus-ui.tree')->name('tree');
    Route::view('bootstrap-notify', 'bonus-ui.bootstrap-notify')->name('bootstrap-notify');
    Route::view('rating', 'bonus-ui.rating')->name('rating');
    Route::view('dropzone', 'bonus-ui.dropzone')->name('dropzone');
    Route::view('tour', 'bonus-ui.tour')->name('tour');
    Route::view('sweet-alert2', 'bonus-ui.sweet-alert2')->name('sweet-alert2');
    Route::view('modal-animated', 'bonus-ui.modal-animated')->name('modal-animated');
    Route::view('owl-carousel', 'bonus-ui.owl-carousel')->name('owl-carousel');
    Route::view('ribbons', 'bonus-ui.ribbons')->name('ribbons');
    Route::view('pagination', 'bonus-ui.pagination')->name('pagination');
    Route::view('breadcrumb', 'bonus-ui.breadcrumb')->name('breadcrumb');
    Route::view('range-slider', 'bonus-ui.range-slider')->name('range-slider');
    Route::view('image-cropper', 'bonus-ui.image-cropper')->name('image-cropper');
    Route::view('sticky', 'bonus-ui.sticky')->name('sticky');
    Route::view('basic-card', 'bonus-ui.basic-card')->name('basic-card');
    Route::view('creative-card', 'bonus-ui.creative-card')->name('creative-card');
    Route::view('tabbed-card', 'bonus-ui.tabbed-card')->name('tabbed-card');
    Route::view('dragable-card', 'bonus-ui.dragable-card')->name('dragable-card');
    Route::view('timeline-v-1', 'bonus-ui.timeline-v-1')->name('timeline-v-1');
    Route::view('timeline-v-2', 'bonus-ui.timeline-v-2')->name('timeline-v-2');
    Route::view('timeline-small', 'bonus-ui.timeline-small')->name('timeline-small');
});

Route::prefix('builders')->group(function () {
    Route::view('form-builder-1', 'builders.form-builder-1')->name('form-builder-1');
    Route::view('form-builder-2', 'builders.form-builder-2')->name('form-builder-2');
    Route::view('pagebuild', 'builders.pagebuild')->name('pagebuild');
    Route::view('button-builder', 'builders.button-builder')->name('button-builder');
});

Route::prefix('animation')->group(function () {
    Route::view('animate', 'animation.animate')->name('animate');
    Route::view('scroll-reval', 'animation.scroll-reval')->name('scroll-reval');
    Route::view('aos', 'animation.aos')->name('aos');
    Route::view('tilt', 'animation.tilt')->name('tilt');
    Route::view('wow', 'animation.wow')->name('wow');
});


Route::prefix('icons')->group(function () {
    Route::view('flag-icon', 'icons.flag-icon')->name('flag-icon');
    Route::view('font-awesome', 'icons.font-awesome')->name('font-awesome');
    Route::view('ico-icon', 'icons.ico-icon')->name('ico-icon');
    Route::view('themify-icon', 'icons.themify-icon')->name('themify-icon');
    Route::view('feather-icon', 'icons.feather-icon')->name('feather-icon');
    Route::view('whether-icon', 'icons.whether-icon')->name('whether-icon');
    Route::view('simple-line-icon', 'icons.simple-line-icon')->name('simple-line-icon');
    Route::view('material-design-icon', 'icons.material-design-icon')->name('material-design-icon');
    Route::view('pe7-icon', 'icons.pe7-icon')->name('pe7-icon');
    Route::view('typicons-icon', 'icons.typicons-icon')->name('typicons-icon');
    Route::view('ionic-icon', 'icons.ionic-icon')->name('ionic-icon');
});

Route::prefix('buttons')->group(function () {
    Route::view('buttons', 'buttons.buttons')->name('buttons');
    Route::view('flat-buttons', 'buttons.flat-buttons')->name('flat-buttons');
    Route::view('edge-buttons', 'buttons.buttons-edge')->name('buttons-edge');
    Route::view('raised-button', 'buttons.raised-button')->name('raised-button');
    Route::view('button-group', 'buttons.button-group')->name('button-group');
});

Route::prefix('forms')->group(function () {
    Route::view('form-validation', 'forms.form-validation')->name('form-validation');
    Route::view('base-input', 'forms.base-input')->name('base-input');
    Route::view('radio-checkbox-control', 'forms.radio-checkbox-control')->name('radio-checkbox-control');
    Route::view('input-group', 'forms.input-group')->name('input-group');
    Route::view('megaoptions', 'forms.megaoptions')->name('megaoptions');
    Route::view('datepicker', 'forms.datepicker')->name('datepicker');
    Route::view('time-picker', 'forms.time-picker')->name('time-picker');
    Route::view('datetimepicker', 'forms.datetimepicker')->name('datetimepicker');
    Route::view('daterangepicker', 'forms.daterangepicker')->name('daterangepicker');
    Route::view('touchspin', 'forms.touchspin')->name('touchspin');
    Route::view('select2', 'forms.select2')->name('select2');
    Route::view('switch', 'forms.switch')->name('switch');
    Route::view('typeahead', 'forms.typeahead')->name('typeahead');
    Route::view('clipboard', 'forms.clipboard')->name('clipboard');
    Route::view('default-form', 'forms.default-form')->name('default-form');
    Route::view('form-wizard', 'forms.form-wizard')->name('form-wizard');
    Route::view('form-two-wizard', 'forms.form-wizard-two')->name('form-wizard-two');
    Route::view('wizard-form-three', 'forms.form-wizard-three')->name('form-wizard-three');
    Route::post('form-wizard-three', function () {
        return redirect()->route('form-wizard-three');
    })->name('form-wizard-three-post');
});

Route::prefix('tables')->group(function () {
    Route::view('bootstrap-basic-table', 'tables.bootstrap-basic-table')->name('bootstrap-basic-table');
    Route::view('bootstrap-sizing-table', 'tables.bootstrap-sizing-table')->name('bootstrap-sizing-table');
    Route::view('bootstrap-border-table', 'tables.bootstrap-border-table')->name('bootstrap-border-table');
    Route::view('bootstrap-styling-table', 'tables.bootstrap-styling-table')->name('bootstrap-styling-table');
    Route::view('table-components', 'tables.table-components')->name('table-components');
    Route::view('datatable-basic-init', 'tables.datatable-basic-init')->name('datatable-basic-init');
    Route::view('datatable-advance', 'tables.datatable-advance')->name('datatable-advance');
    Route::view('datatable-styling', 'tables.datatable-styling')->name('datatable-styling');
    Route::view('datatable-ajax', 'tables.datatable-ajax')->name('datatable-ajax');
    Route::view('datatable-server-side', 'tables.datatable-server-side')->name('datatable-server-side');
    Route::view('datatable-plugin', 'tables.datatable-plugin')->name('datatable-plugin');
    Route::view('datatable-api', 'tables.datatable-api')->name('datatable-api');
    Route::view('datatable-data-source', 'tables.datatable-data-source')->name('datatable-data-source');
    Route::view('datatable-ext-autofill', 'tables.datatable-ext-autofill')->name('datatable-ext-autofill');
    Route::view('datatable-ext-basic-button', 'tables.datatable-ext-basic-button')->name('datatable-ext-basic-button');
    Route::view('datatable-ext-col-reorder', 'tables.datatable-ext-col-reorder')->name('datatable-ext-col-reorder');
    Route::view('datatable-ext-fixed-header', 'tables.datatable-ext-fixed-header')->name('datatable-ext-fixed-header');
    Route::view('datatable-ext-html-5-data-export', 'tables.datatable-ext-html-5-data-export')->name('datatable-ext-html-5-data-export');
    Route::view('datatable-ext-key-table', 'tables.datatable-ext-key-table')->name('datatable-ext-key-table');
    Route::view('datatable-ext-responsive', 'tables.datatable-ext-responsive')->name('datatable-ext-responsive');
    Route::view('datatable-ext-row-reorder', 'tables.datatable-ext-row-reorder')->name('datatable-ext-row-reorder');
    Route::view('datatable-ext-scroller', 'tables.datatable-ext-scroller')->name('datatable-ext-scroller');
    Route::view('jsgrid-table', 'tables.jsgrid-table')->name('jsgrid-table');
});

Route::prefix('charts')->group(function () {
    Route::view('echarts', 'charts.echarts')->name('echarts');
    Route::view('chart-apex', 'charts.chart-apex')->name('chart-apex');
    Route::view('chart-google', 'charts.chart-google')->name('chart-google');
    Route::view('chart-sparkline', 'charts.chart-sparkline')->name('chart-sparkline');
    Route::view('chart-flot', 'charts.chart-flot')->name('chart-flot');
    Route::view('chart-knob', 'charts.chart-knob')->name('chart-knob');
    Route::view('chart-morris', 'charts.chart-morris')->name('chart-morris');
    Route::view('chartjs', 'charts.chartjs')->name('chartjs');
    Route::view('chartist', 'charts.chartist')->name('chartist');
    Route::view('chart-peity', 'charts.chart-peity')->name('chart-peity');
});

Route::view('sample-page', 'pages.sample-page')->name('sample-page');
Route::view('internationalization', 'pages.internationalization')->name('internationalization');

// Route::prefix('starter-kit')->group(function () {
// });

Route::prefix('others')->group(function () {
    Route::view('400', 'errors.400')->name('error-400');
    Route::view('401', 'errors.401')->name('error-401');
    Route::view('403', 'errors.403')->name('error-403');
    Route::view('404', 'errors.404')->name('error-404');
    Route::view('500', 'errors.500')->name('error-500');
    Route::view('503', 'errors.503')->name('error-503');
});

// Route::prefix('authentication')->group(function () {
//     Route::view('login', 'authentication.login')->name('login');
//     Route::view('login-one', 'authentication.login-one')->name('login-one');
//     Route::view('login-two', 'authentication.login-two')->name('login-two');
//     Route::view('login-bs-validation', 'authentication.login-bs-validation')->name('login-bs-validation');
//     Route::view('login-bs-tt-validation', 'authentication.login-bs-tt-validation')->name('login-bs-tt-validation');
//     Route::view('login-sa-validation', 'authentication.login-sa-validation')->name('login-sa-validation');
//     Route::view('sign-up', 'authentication.sign-up')->name('sign-up');
//     Route::view('sign-up-one', 'authentication.sign-up-one')->name('sign-up-one');
//     Route::view('sign-up-two', 'authentication.sign-up-two')->name('sign-up-two');
//     Route::view('sign-up-wizard', 'authentication.sign-up-wizard')->name('sign-up-wizard');
//     Route::view('unlock', 'authentication.unlock')->name('unlock');
//     Route::view('forget-password', 'authentication.forget-password')->name('forget-password');
//     Route::view('reset-password', 'authentication.reset-password')->name('reset-password');
//     Route::view('maintenance', 'authentication.maintenance')->name('maintenance');
// });

Route::view('comingsoon', 'comingsoon.comingsoon')->name('comingsoon');
Route::view('comingsoon-bg-video', 'comingsoon.comingsoon-bg-video')->name('comingsoon-bg-video');
Route::view('comingsoon-bg-img', 'comingsoon.comingsoon-bg-img')->name('comingsoon-bg-img');

Route::view('basic-template', 'email-templates.basic-template')->name('basic-template');
Route::view('email-header', 'email-templates.email-header')->name('email-header');
Route::view('template-email', 'email-templates.template-email')->name('template-email');
Route::view('template-email-2', 'email-templates.template-email-2')->name('template-email-2');
Route::view('ecommerce-templates', 'email-templates.ecommerce-templates')->name('ecommerce-templates');
Route::view('email-order-success', 'email-templates.email-order-success')->name('email-order-success');


Route::prefix('gallery')->group(function () {
    Route::view('index', 'apps.gallery')->name('gallery');
    Route::view('with-gallery-description', 'apps.gallery-with-description')->name('gallery-with-description');
    Route::view('gallery-masonry', 'apps.gallery-masonry')->name('gallery-masonry');
    Route::view('masonry-gallery-with-disc', 'apps.masonry-gallery-with-disc')->name('masonry-gallery-with-disc');
    Route::view('gallery-hover', 'apps.gallery-hover')->name('gallery-hover');
});

Route::prefix('blog')->group(function () {
    Route::view('index', 'apps.blog')->name('blog');
    Route::view('blog-single', 'apps.blog-single')->name('blog-single');
    Route::view('add-post', 'apps.add-post')->name('add-post');
});


Route::view('faq', 'apps.faq')->name('faq');

Route::prefix('job-search')->group(function () {
    Route::view('job-cards-view', 'apps.job-cards-view')->name('job-cards-view');
    Route::view('job-list-view', 'apps.job-list-view')->name('job-list-view');
    Route::view('job-details', 'apps.job-details')->name('job-details');
    Route::view('job-apply', 'apps.job-apply')->name('job-apply');
});

Route::prefix('learning')->group(function () {
    Route::view('learning-list-view', 'apps.learning-list-view')->name('learning-list-view');
    Route::view('learning-detailed', 'apps.learning-detailed')->name('learning-detailed');
});

Route::prefix('maps')->group(function () {
    Route::view('map-js', 'apps.map-js')->name('map-js');
    Route::view('vector-map', 'apps.vector-map')->name('vector-map');
});

Route::prefix('editors')->group(function () {
    Route::view('summernote', 'apps.summernote')->name('summernote');
    Route::view('ckeditor', 'apps.ckeditor')->name('ckeditor');
    Route::view('simple-mde', 'apps.simple-mde')->name('simple-mde');
    Route::view('ace-code-editor', 'apps.ace-code-editor')->name('ace-code-editor');
});

Route::view('knowledgebase', 'apps.knowledgebase')->name('knowledgebase');
Route::view('support-ticket', 'apps.support-ticket')->name('support-ticket');
Route::view('landing-page', 'pages.landing-page')->name('landing-page');

Route::prefix('layouts')->group(function () {
    Route::view('compact-sidebar', 'admin_unique_layouts.compact-sidebar'); //default //Dubai
    Route::view('box-layout', 'admin_unique_layouts.box-layout');    //default //New York //
    Route::view('dark-sidebar', 'admin_unique_layouts.dark-sidebar');

    Route::view('default-body', 'admin_unique_layouts.default-body');
    Route::view('compact-wrap', 'admin_unique_layouts.compact-wrap');
    Route::view('enterprice-type', 'admin_unique_layouts.enterprice-type');

    Route::view('compact-small', 'admin_unique_layouts.compact-small');
    Route::view('advance-type', 'admin_unique_layouts.advance-type');
    Route::view('material-layout', 'admin_unique_layouts.material-layout');

    Route::view('color-sidebar', 'admin_unique_layouts.color-sidebar');
    Route::view('material-icon', 'admin_unique_layouts.material-icon');
    Route::view('modern-layout', 'admin_unique_layouts.modern-layout');
});

Route::get('layout-{light}', function ($light) {
    session()->put('layout', $light);
    session()->get('layout');
    if ($light == 'vertical-layout') {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('home');
});
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');
