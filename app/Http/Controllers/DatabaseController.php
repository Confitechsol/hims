<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class DatabaseController extends Controller
{
    public function listBackups()
{
    $files = Storage::files('backups');

    $backups = collect($files)->map(function ($file) {
        return [
            'name' => basename($file),
            'path' => $file,
            'size' => Storage::size($file),
            'url' => route('database.download', basename($file)),
        ];
    });
    return view('admin.backups.index', compact('backups'));
}
public function download($filename)
{
    $filePath = 'backups/' . $filename;

    if (!Storage::exists($filePath)) {
        return redirect()->back()->with('error', 'File not found.');
    }

    return Storage::download($filePath);
}

public function delete($filename)
{
    $filePath = 'backups/' . $filename;

    if (Storage::exists($filePath)) {
        Storage::delete($filePath);
        return redirect()->back()->with('success', 'Backup deleted successfully.');
    }

    return redirect()->back()->with('error', 'Backup not found.');
}

    public function backup()
    {
        $db = config('database.connections.mysql');

        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $storagePath = storage_path("app/backups");
        $filePath = "{$storagePath}/{$fileName}";

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        if (!is_writable($storagePath)) {
            return redirect()->back()->with('error', 'Backup directory is not writable.');
        }
        // Ensure backup directory exists
        Storage::makeDirectory('backups');
        $mysqldumpPath = '"' . env('MYSQLDUMP_PATH') . '"';

        // Build the dump command
        $command = sprintf(
            '%s --user=%s --password=%s --host=%s %s > %s',
            $mysqldumpPath,
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['host']),
            escapeshellarg($db['database']),
            escapeshellarg($filePath)
        );

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return redirect()->back()->with('error', 'Backup failed: ' . $process->getErrorOutput());
        }

        // return response()->download($filePath)->deleteFileAfterSend(true);
        return redirect()->back()->with('success', 'Database Backup Created Successfully.');
    }

    // Optional: Adjust your restore() method to accept a filename (if not already supporting it)
public function restore(Request $request)
{
    if ($request->hasFile('backup_file')) {
        // original upload-based restore
        $request->validate([
            'backup_file' => 'required|file|mimes:sql',
        ]);

        $file = $request->file('backup_file');
        $filePath = $file->getRealPath();
    } elseif ($request->has('filename')) {
        // restore from existing file
        $filename = $request->input('filename');
        $filePath = storage_path("app/backups/" . $filename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file does not exist.');
        }
    } else {
        return redirect()->back()->with('error', 'No backup file provided.');
    }

    $db = config('database.connections.mysql');
    $mysqldumpPath = '"' . env('MYSQLDUMP_PATH') . '"';
    $command = sprintf(
        // 'mysql --user=%s --password=%s --host=%s %s < %s',
        '%s --user=%s --password=%s --host=%s %s < %s',
        $mysqldumpPath,
        escapeshellarg($db['username']),
        escapeshellarg($db['password']),
        escapeshellarg($db['host']),
        escapeshellarg($db['database']),
        escapeshellarg($filePath)
    );

    $process = Process::fromShellCommandline($command);
    $process->run();

    if (!$process->isSuccessful()) {
        return redirect()->back()->with('error', 'Restore failed: ' . $process->getErrorOutput());
    }

    return redirect()->back()->with('success', 'Database restored successfully.');
}
}
