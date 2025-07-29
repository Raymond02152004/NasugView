<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportedFile;
use Illuminate\Support\Facades\Storage;

class ImportFileController extends Controller
{
    public function bulkDelete(Request $request)
    {
        $filenames = json_decode($request->input('filenames'), true);

        if (!$filenames || !is_array($filenames)) {
            return back()->with('error', 'No files selected.');
        }

        foreach ($filenames as $filename) {
            // Delete from DB
            ImportedFile::where('filename', $filename)->delete();

            // Delete file from storage
            $path = 'public/csv_uploads/' . $filename;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        return back()->with('success', 'Files deleted successfully.');
    }
}
