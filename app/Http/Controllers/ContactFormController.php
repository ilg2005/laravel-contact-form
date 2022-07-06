<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Mail;

class ContactFormController extends Controller
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function StoreData($request): void
    {
        // Validate form data
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:5',
            'subject'=>'required',
            'message' => 'required'
        ]);

        // Store form data
        Contact::create($request->all());
    }

    public function SendMail($request): void
    {
        //  Send mail to user with cc to admin
        \Mail::send('mail', [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'subject' => $request->get('subject'),
            'user_query' => $request->get('message'),
        ], function ($message) use ($request) {
            $message->from(config('mail.mailers.smtp.username'), 'Admin');
            $message->to($request->email)->subject($request->get('subject'));
            $message->cc(config('mail.mailers.smtp.username'))->subject($request->get('subject'));
        });
    }

    public function ProcessContactForm()
    {
        $this->StoreData($this->request);
        $this->SendMail($this->request);
        return back()->with('success', 'Ваше сообщение отправлено!');
    }
}
