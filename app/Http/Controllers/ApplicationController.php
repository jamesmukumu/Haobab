<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\application;
use App\Mail\ApplicationMail;
use Mail;
use App\Mail\Notify;

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
                'applicationDocs.*' => 'file|mimes:pdf,doc,docx,png,jpeg,webp|max:8192', 
            ]);
    
            $files = $request->file('applicationDocs');
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



}
