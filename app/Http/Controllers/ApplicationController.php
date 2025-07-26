<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipStream\ZipStream;
use Log;
use App\Models\application;
use App\Mail\ApplicationMail;
use Mail;
use App\Mail\Notify;
use Cloudinary\Cloudinary;


class ApplicationController extends Controller{

    public function fileSave($files)
    {
        $filePaths = [];
        if (is_array($files)) { 
            foreach ($files as $singleFile) {
                if ($singleFile->isValid()) {
                    $path = $singleFile->store('application_files', 'local');
                    $filePaths[] = $path;
                }
            }
        } elseif ($files && $files->isValid()) { 
            $path = $files->store('application_files', 'local');
            $filePaths[] = $path;
        }
        return $filePaths;
    }


    public function SaveApplication(Request $request)
    {
        try {
          $cld = new Cloudinary();
            $validatedRequest = $request->validate([
                'fullNames' => 'required|string',
                'emailAddress' => 'required|email',
                'phoneNumber' => 'required|string',
                'location' => 'required|string',
                'intro' => 'required|string',
                'workExperiences' => 'required', 
                'salaryExpectations' => 'required',
                'DOB' => 'required|date',
                'applicationDocs' => 'required', 
                'applicationDocs.*' => 'file|max:8192', 
            ]);
    
            $files = $request->file('applicationDocs');

            $passportPath = $cld->uploadApi()->upload($request->file("passportPhoto",[
            "folder"=>"Hoabab"
            ])->getRealPath());
            $validatedRequest['passportPhoto'] = $passportPath['secure_url'];
            if (empty($files)) {
                return response()->json([
                    'message' => 'No Application files uploaded',
                ], 400);
            }
    
            $paths = $this->fileSave($files);
            if (empty($paths)) {
                return response()->json([
                    'message' => 'Failed to save files',
                ], 500);
            }
    
           
            $validatedRequest['resumePath'] = json_encode($paths);
            $validatedRequest['workExperiences'] = json_encode($validatedRequest['workExperiences']);
            $mailer = new ApplicationMail();
            $mailer_notify = new Notify();
        Mail::to($validatedRequest['emailAddress'])->send($mailer);
        Mail::to("jamesmukumu03@gmail.com")->send($mailer_notify);
            Application::create($validatedRequest);
    
            return response()->json([
                'message' => 'Application Received',

            ], 201);
    
        } catch (\Exception $err) {
            Log::error('Error saving application: ' . $err->getMessage());
            return response()->json([
                'message' => 'Something has gone wrong',
                'error' => $err->getMessage(), 
            ], 500);
        }
    }



    public function fetchApplications(Request $request){
    try{
     $appplications =  application::all();
      return response()->json([
      "message"=>"Applications fetched",
      "data"=>$appplications
      ]);
    }catch(\Exception $err){
Log::error($err);
return response()->json([
"message"=>"Something went wrong"
]);
}}

public function downloadDocuments(Request $request)
{
    try {
        // Get applicant ID from query parameter
        $applicant_id = $request->query("id");
        $application = Application::findOrFail($applicant_id);
        $profile_photo = $application['passportPhoto'];

        // Decode the resumePath JSON
        $file_paths = json_decode($application->resumePath, true);
        Log::info('File paths: ' . json_encode($file_paths));

        // Check if file_paths is an array and not empty
        if (!is_array($file_paths) || empty($file_paths)) {
            return response()->json([
                "message" => "No files found for this application"
            ], 404);
        }

        // Create a new ZipStream instance
        $zipFileName = 'documents_' . $applicant_id . '_' . time() . '.zip';

        // Return a streaming response
        return response()->streamDownload(function () use ($file_paths, $zipFileName) {
            $zip = new ZipStream(
                outputName: $zipFileName,
                sendHttpHeaders: false
            );

            $filesAdded = false;

            // Loop through each file path
            foreach ($file_paths as $index => $filePath) {
                if (Storage::disk('local')->exists($filePath)) {
                    $fileName = basename($filePath);
                    $fileContent = Storage::disk('local')->get($filePath);
                    if ($fileContent === false) {
                        \Log::error("Failed to read file: $filePath");
                        continue;
                    }
                    $zip->addFile('document_' . ($index + 1) . '_' . $fileName, $fileContent);
                    $filesAdded = true;
                } else {
                    \Log::warning("File not found: $filePath");
                }
            }

            if (!$filesAdded) {
                throw new \Exception("No valid files found for download");
            }

            $zip->finish();
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
        ]);

    } catch (\Exception $err) {
        \Log::error('Error downloading documents: ' . $err->getMessage());
        return response()->json([
            "message" => "Something has gone wrong",
            "error" => $err->getMessage()
        ], 500);
    }
}


}
