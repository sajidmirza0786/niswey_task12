<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $contacts = Contact::query();
            if ($request->has('trash')) {
                $contacts = $contacts->onlyTrashed();
            }
            if($request->has('phone')){
                $contacts->where('phone', 'like', '%' . $request->phone . '%');
            } 
            $contacts = $contacts->orderByDesc('id')->paginate(20)->withQueryString();
            return view('contacts.index', compact('contacts'));
        }catch(\Exception $e){
            return back()->with('error', 'failled to reterieve contacts'. $e->getMessage());
        }
    }


    public function upload(Request $request)
    {
        $request->validate([
            'xml_file' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $xmlContent = file_get_contents($request->file('xml_file'));
            $xml = simplexml_load_string($xmlContent, "SimpleXMLElement", LIBXML_NOCDATA);

            if (!$xml || !isset($xml->contact)) {
                return back()->with('error', 'Invalid XML format.');
            }

            foreach ($xml->contact as $contact) {
                $name = trim((string) $contact->name);
                $phone = preg_replace('/\s+/', '', (string) $contact->phone);
                $createdAt = now(); 

                Contact::updateOrCreate(['phone'=>$phone],[
                    'name'       => $name,
                    'phone'      => $phone,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Contacts imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        if(!$contact){
            abort(404);
        }
        $validator = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'phone' => ['required', 'max:15', 'min:10', 'unique:contacts,phone'],
        ]);

        try{
            DB::beginTransaction();
            $contact->update($validator);
            DB::commit();
            return redirect(route('contacts.index'))->with('success', 'Contacts updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error' . $e->getMessage());
        }
    }

    public function recover($id)
    {
        try {
            $contact = Contact::withTrashed()->find($id);

            if(!$contact){
                return back()->with('error', 'contact not found');
            }

            if ($contact->trashed()) {
                $contact->restore();
                return redirect()->route('contacts.index')
                    ->with('success', 'Contact has been successfully recovered.');
            }

            return redirect()->route('contacts.index')->with('info', 'Contact is not in trash.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to recover contact: ' . $e->getMessage());
        }
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return back()->with('success', 'Contact deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete contact. ' . $e->getMessage());
        }
    }

    public function forceDelete(Contact $contact)
    {
        try {
            $contact->forceDelete();
            return back()->with('success', 'Contact permanently deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to permanently delete contact: ' . $e->getMessage());
        }
    }
}
