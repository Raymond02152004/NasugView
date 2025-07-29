<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessOwnerAccount;
use App\Models\Signup;
use App\Models\Login;
use App\Models\ImportedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function showImportPage()
    {
        $files = ImportedFile::orderBy('imported_id', 'desc')->get();
        return view('admin.account', compact('files'));
    }

    public function showAccountList()
    {
        $users = BusinessOwnerAccount::all();
        $files = ImportedFile::orderBy('imported_id', 'desc')->get();
        return view('admin.accountlist', compact('users', 'files'));
    }

    public function importAccounts(Request $request)
    {
        Log::info("🚀 importAccounts method triggered");

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . $originalName;

        $file->storeAs('csv_uploads', $filename, 'public');
        Log::info("📁 File stored: {$filename}");

        $imported = ImportedFile::create(['filename' => $filename]);
        if (!$imported) {
            Log::error("❌ Failed to insert filename to imported_file table.");
            return back()->with('error', 'Failed to track uploaded file.');
        }

        $csvPath = storage_path("app/public/csv_uploads/{$filename}");
        if (!file_exists($csvPath)) {
            Log::error("❌ CSV file not found at: {$csvPath}");
            return back()->with('error', 'CSV file missing.');
        }

        $raw = file_get_contents($csvPath);
        Log::info("🧾 Raw File Content:\n" . $raw);

        $csv = array_map('str_getcsv', file($csvPath));
        $header = array_shift($csv);

        foreach ($csv as $index => $row) {
            Log::info("🔍 Processing row {$index}: " . json_encode($row));

            if (count($row) < 4) {
                Log::warning("⚠️ Row {$index} skipped: Not enough columns.");
                continue;
            }

            [$businessName, $businessType, $businessAddress, $csvEmail] = array_map('trim', $row);

            if (empty($businessName) || empty($csvEmail)) {
                Log::warning("⚠️ Row {$index} skipped: Missing business name or email.");
                continue;
            }

            $existing = BusinessOwnerAccount::where('business_name', $businessName)->first();
            if ($existing) {
                Log::info("⏩ Skipped duplicate business: {$businessName}");
                continue;
            }

            try {
                $generatedEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $businessName)) . '@gmail.com';
                $generatedPassword = Str::random(6);

                BusinessOwnerAccount::create([
                    'business_name'    => $businessName,
                    'business_type'    => $businessType,
                    'business_address' => $businessAddress,
                    'email'            => $csvEmail,
                ]);

                $signup = Signup::create([
                    'email'    => $generatedEmail,
                    'username' => $businessName,
                    'password' => Hash::make($generatedPassword),
                    'role'     => 'business owner',
                    'profile_pic' => 'default.png',
                ]);

                Login::create(['signup_id' => $signup->signup_id]);

                Mail::raw(
                    "Welcome to NasugView!\n\nYour login credentials:\nEmail: {$generatedEmail}\nPassword: {$generatedPassword}",
                    function ($message) use ($csvEmail, $businessName) {
                        $message->to($csvEmail)
                                ->subject("Your NasugView Business Account Credentials");
                    }
                );

                Log::info("✅ Inserted business '{$businessName}' and sent email to {$csvEmail}");

            } catch (\Exception $e) {
                Log::error("❌ Error on row {$index}: " . $e->getMessage());
                continue;
            }
        }

        return redirect()->route('admin.accountlist')
            ->with('success', 'Import complete. Accounts created and emails sent!')
            ->with('imported', true);
    }

    public function viewImportedFile($filename)
    {
        $filePath = storage_path('app/public/csv_uploads/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $rows = array_map('str_getcsv', file($filePath));

        return view('admin.viewimportedfile', [
            'filename' => $filename,
            'rows' => $rows
        ]);
    }

    public function deleteSelected(Request $request)
    {
        $fileIds = $request->input('file_ids');

        if ($fileIds && is_array($fileIds)) {
            $files = ImportedFile::whereIn('imported_id', $fileIds)->get();

            foreach ($files as $file) {
                $path = storage_path("app/public/csv_uploads/{$file->filename}");
                Log::info("🗑 Deleting file: {$file->filename}");

                if (file_exists($path)) {
                    unlink($path);
                }

                $file->delete();
            }
        }

        return redirect()->route('admin.accountlist')->with('deleted', true);
    }
}
