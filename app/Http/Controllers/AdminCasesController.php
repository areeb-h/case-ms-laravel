<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cases;
use App\Models\Document;
use App\Models\Hearing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminCasesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->input('search_query');
            $type = $request->input('case_type');
            $status = $request->input('status');

            // Build the query based on the filters
            $cases = Cases::query();

            if ($query) {
                $cases->where('case_title', 'LIKE', "%{$query}%")
                    ->orWhere('case_number', 'LIKE', "%{$query}%")
                    ->orWhere('case_type', 'LIKE', "%{$query}%")
                    ->orWhere('status', 'LIKE', "%{$query}%");
            }

            if ($type) {
                $cases->where('case_type', 'LIKE', $type);
            }

            if ($status !== null) {
                $cases->where('status', $status);
            }

            $cases = $cases->get();
            return view('partials.case_rows', ['cases' => $cases]);
        }
        return view('admin.cases.index');
    }

    public function create()
    {
        $lawyers = User::role('lawyer')->get();
        $clients = User::role('client')->get();

        return view('admin.cases.create', [
            'lawyers' => $lawyers,
            'clients' => $clients
        ]);
    }


    public function edit($id)
    {
        $case = Cases::findOrFail($id);
        $created_at = Carbon::parse($case->created_at);
        $documents = Document::where('case_id', $id)->get()->groupBy('document_type');
        $actions = ActionLog::where('case_id', $id)->with('user')->orderBy('created_at', 'desc')->get();
        $lawyers = User::role('lawyer')->get();
        $judges = User::role('Judge')->get();
        $hearings = Hearing::where('case_id', $id)->get();

        $now = Carbon::now();
        $diffInDays = $now->diffInDays($created_at);
        $diffInHours = $now->diffInHours($created_at) - ($diffInDays * 24);

        return view('admin.cases.edit', [
            'case' => $case,
            'documents' => $documents,
            'lawyers' => $lawyers,
            'diffInDays' => $diffInDays,
            'diffInHours' => $diffInHours,
            'actions' => $actions,
            'judges' => $judges,
            'hearings' => $hearings
        ]);
    }

    public function updateCaseStatus(Request $request, $id)
    {
        // Find the case
        $case = Cases::findOrFail($id);

        // Update the status
        $oldStatus = $case->status;
        $case->status = $request->input('case_status');
        $case->save();

        // Log the action
        ActionLog::create([
            'user_id' => auth()->id(),
            'case_id' => $id,
            'action_type' => 'Case Status Modified',
            'description' => 'Changed from: ' . $oldStatus . ' to ' . $case->status,
        ]);
        // Redirect back with a success message
        return redirect()->to(url()->previous() . '#actions')->with('success', 'Case status updated successfully');

    }

    public function updateDescription(Request $request, $id)
    {
        $case = Cases::findOrFail($id);
        $oldDescription = $case->description;
        $case->description = $request->input('description');
        $case->save();

        ActionLog::create([
            'user_id' => auth()->id(),
            'case_id' => $id,
            'action_type' => 'Description Modified',
            'description' => 'Modified: [' . substr($oldDescription, 0, 10) . '...]',
        ]);

        return redirect()->back()->with('success', 'Description updated successfully');
    }

    public function reassignLawyer(Request $request, $id)
    {
        $newLawyerId = $request->input('new_lawyer_id');

        // Find the case
        $case = Cases::findOrFail($id);

        $oldLawyer = $case->lawyer->name;

        // Reassign the lawyer
        $case->lawyer_id = $newLawyerId;
        $case->save();

        ActionLog::create([
            'user_id' => auth()->id(),
            'case_id' => $id,
            'action_type' => 'Case Reassigned',
            'description' => 'Reassigned from: [' . substr($oldLawyer, 0, 10) . '...]',
        ]);

        // Redirect with a message
        return redirect()->back()->with('success', 'Lawyer reassigned successfully.');
    }

    public function uploadDocument(Request $request, $id)
    {
        try {
            $request->validate([
                'document' => 'required|mimes:pdf,doc,docx,jpg,png|max:10240', // 10MB limit
                'document_type' => 'required|in:Case Report,Others', // Validating the document_type
            ]);

            $case = Cases::findOrFail($id);

            $document_name = $request->file('document')->getClientOriginalName();

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $path = $file->storeAs(
                    'cases/' . $case->case_number,
                    $file->getClientOriginalName(),
                    'public'
                );

                Document::create([
                    'case_id' => $id,
                    'document_path' => $path,
                    'document_name' => $file->getClientOriginalName(),
                    'document_type' => $request->input('document_type'), // Store the document type
                ]);

                ActionLog::create([
                    'user_id' => auth()->id(),
                    'case_id' => $id,
                    'action_type' => 'Document Uploaded',
                    'description' => 'Uploaded: [' . substr($document_name, 0, 10) . '...]',
                ]);
            }

            return redirect()->to(url()->previous() . '#documents')->with('success', 'Document uploaded successfully');
        } catch (Exception $e) {
            return redirect()->to(url()->previous() . '#documents')->with('error', 'Failed to upload document');
        }
    }

    public function deleteDocument($id) {
        try {
            // Find the document by its ID
            $document = Document::findOrFail($id);

            $caseId = $document->case_id;
            $documentName = $document->document_name;

            // Delete the file from storage
            Storage::delete($document->document_path);

            // Delete the record from the database
            $document->delete();

            // Log the action
            ActionLog::create([
                'user_id' => auth()->id(),
                'case_id' => $caseId,
                'action_type' => 'Document Deleted',
                'description' => 'Deleted: [' . substr($documentName, 0, 10) . '...]',
            ]);

            return redirect()->to(url()->previous() . '#documents')->with('success', 'Document deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to(url()->previous() . '#documents')->with('error', 'Failed to delete document');
        }
    }

    public function createHearing(Request $request, $id)
    {
        try {
            $request->validate([
                'judge_id' => 'required|integer',
                'scheduled_time' => 'required|date_format:Y-m-d\TH:i',
                'notes' => 'nullable|string',
            ]);

            Hearing::create([
                'case_id' => $id,
                'judge_id' => $request->input('judge_id'),
                'scheduled_time' => Carbon::parse($request->input('scheduled_time')),
                'notes' => $request->input('notes'),
            ]);

            // Log the action
            ActionLog::create([
                'user_id' => auth()->id(),
                'case_id' => $id,
                'action_type' => 'Hearing Scheduled',
                'description' => 'Scheduled a Hearing',
            ]);

            return redirect()->to(url()->previous() . '#hearings')->with('success', 'Hearing scheduled successfully');
        } catch (Exception $e) {
            return redirect()->to(url()->previous() . '#hearings')->with('error', 'Failed to schedule hearing');
        }
    }

    public function deleteHearing($id)
    {
        try {
            // Find the hearing by its ID
            $hearing = Hearing::findOrFail($id);

            // Delete the hearing
            $hearing->delete();

            // Log the action (optional)
            ActionLog::create([
                'user_id' => auth()->id(),
                'case_id' => $hearing->case_id,
                'action_type' => 'Hearing Deleted',
                'description' => 'Deleted a Hearing',
            ]);

            return redirect()->to(url()->previous() . '#hearings')->with('success', 'Hearing deleted successfully');
        } catch (Exception $e) {
            return redirect()->to(url()->previous() . '#hearings')->with('error', 'Failed to delete hearing');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'case_number' => 'required|string|max:255',
                'case_title' => 'required|string|max:255',
                'case_type' => 'required|string',
                'case_role' => 'required|string',
                'client_id' => 'required|integer',
                'lawyer_id' => 'required|integer',
                'description' => 'nullable|string'
            ]);

            $validated['status'] = 'Open';

            $case = Cases::create($validated);

            ActionLog::create([
                'user_id' => auth()->id(),
                'case_id' => $case->id,
                'action_type' => 'Case Created',
                'description' => 'Created: [' . substr($case->case_title, 0, 10) . '...]',
            ]);

            return redirect()->route('admin.cases')->with('success', 'Case created successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.cases')->with('error', 'Failed to create case');
        }
    }

    public function destroy($id)
    {
        $case = Cases::findOrFail($id);
        $case->delete();

        return redirect()->route('admin.cases.index')->with('success', 'Case deleted successfully');
    }
}
