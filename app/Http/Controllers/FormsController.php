<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\RequestDocumentationExampleFormRequest;
use Illuminate\Support\Facades\Mail;

class FormsController extends Controller
{
    public function contact(ContactFormRequest $request, $subject = 'Contact')
    {
        $data = $request->validated();
        $content = strip_tags($data['text'], "<br>");

        $send = Mail::send('mails.layout', compact('content'), function ($message) use ($subject, $data) {

            $message->from(config('mail.from.address'), $data['name']);
            $message->replyTo($data['email'], $data['name']);

            $message->to(config('mail.admin_mail'))->subject($subject);
        });

        if ($send) {
            return redirect()->route('pages.contact')->with('success', 'We will try to get back to you within 24 hours, if not sooner.');
        } else {
            return redirect()->route('pages.contact')->with('danger', 'Error');
        }
    }
}
