<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contact;
use Mail;
class ContactFormController extends Controller {

    public function UploadForm(Request $request) {
        // Store Contact Form data
        Contact::create($request->all());

        //  Send mail to user cc to admin
        \Mail::send('mail', [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'subject' => $request->get('subject'),
            'user_query' => $request->get('message'),
        ], function($message) use ($request){
            $message->from(config('mail.mailers.smtp.username'), 'Admin');
            $message->to($request->email)->subject($request->get('subject'));
            $message->cc(config('mail.mailers.smtp.username'))->subject($request->get('subject'));
        });
        return back()->with('success', 'Ваше сообщение отправлено!');
    }
}
