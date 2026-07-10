// web.php
// TESTER S3
Route::get('/test-upload-s3', function () {
    $localPath = public_path('sample/2 kb.pdf');
    if (!File::exists($localPath)) {
        return "Gagal: File tidak ditemukan di " . $localPath;
    }

    try {
        $content = File::get($localPath);
        $s3Path = 'sample/done.pdf';
        $upload = Storage::disk('s3')->put($s3Path, $content);

        if ($upload) {
            $url = Storage::disk('s3')->url($s3Path);
            return response()->json([
                'status' => 'Berhasil!',
                's3_url' => $url
            ]);
        } else {
            return "Upload gagal (Return False). Cek konfigurasi .env atau log.";
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Error Catch',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/test-view-s3', function () {
    $s3Path = 'sample/done.pdf';

    try {
        if (Storage::disk('s3')->exists($s3Path)) {
            $temporaryUrl = Storage::disk('s3')->temporaryUrl(
                $s3Path, now()->addMinutes(15)
            );

            return response()->json([
                'status' => 'File ditemukan!',
                'keterangan' => 'Link ini akan expired dalam 15 menit',
                'url_sakti' => $temporaryUrl
            ]);
        }

        return "File tidak ditemukan di S3 pada path: " . $s3Path;

    } catch (\Exception $e) {
        return $e->getMessage();
    }
});


// LamaranController.php
// Ini upload file ke S3, lalu simpan URL-nya ke database
if ($request->hasFile('nodin_1')) {
    try {
        $file = $request->file('nodin_1');
        $filename = $this->generateSafeFilename($file, 'nodin_1');
        // Simpan file dan dapatkan path lengkap (seperti di RegisterController)
        // $nodinPath = $file->storeAs('nodin_1', $filename, 'public');
        $sPath = Storage::disk('s3')->putFileAs('nodin_1', $file, $filename, 'private');

        // Log untuk debugging
        Log::info('Nodin file uploaded', [
            'filename' => $filename,
            'path' => $nodinPath,
            'spath' => $sPath,
            'lamaran_id' => $id
        ]);
    } catch (\Exception $e) {
        Log::error('Error uploading nodin file: ' . $e->getMessage());
        throw $e;
    }
}

// DashboardController.php
// Ini untuk show file dari S3

$peserta = null;
if ($data->status == 3) {
    $peserta = Peserta::where('id_lamaran', $id)->first();
    if($peserta->nodin_1){
        if (Storage::disk('s3')->exists($peserta->nodin_1)) {
            $peserta->nodin_1_url = Storage::disk('s3')->temporaryUrl(
                $peserta->nodin_1, now()->addMinutes(15)
            );
        } else {
            $peserta->nodin_1_url = "File tidak ditemukan di S3 pada path: " . $peserta->nodin_1;
        }
        // $peserta->nodin_1_url = Storage::disk('s3')->temporaryUrl($peserta->nodin_1, now()->addMinutes(15));
    }
}
