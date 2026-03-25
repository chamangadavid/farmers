<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\Contacts\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function ContactUs()
    {
        return Inertia::render('MyAAIB/Contacts/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Send email to admin
        $adminEmails = config('mail.admin_addresses.aaib'); // 'umoyoprintex@gmail.com'
        if ($adminEmails) {
            Mail::to($adminEmails)->send(new ContactFormMail($contact->toArray()));
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }

    // Fetch all contacts
    // public function index()
    // {
    //     $contacts = Contact::orderBy('created_at', 'desc')->get();
    //     return response()->json(['contacts' => $contacts]);
    // }


    public function index(Request $request)
{
    $query = Contact::query();

    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhere('message', 'like', "%{$search}%");
        });
    }

    $contacts = $query->orderBy('created_at', 'desc')->get();

    return response()->json(['contacts' => $contacts]);
}
    // Delete a contact
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['success' => true, 'message' => 'Contact deleted successfully']);
    }


    


}
