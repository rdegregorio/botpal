<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatConfigRequest;
use App\Models\ChatConfig;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Storage;

class ChatConfigsController extends Controller
{
    public function index()
    {
        $chatConfig = auth()->user()->chatConfigLatest;

        return view('dashboard', compact('chatConfig'));
    }
    public function settings()
    {
        $chatConfig = auth()->user()->chatConfigLatest;
        return view('settings', compact('chatConfig'));
    }
    public function knowledge(Request $request)
    {
        $chatConfig = auth()->user()->chatConfigLatest;

        if(!$chatConfig) {
            return redirect()->route('settings');
        }

        $uploadDir = "chat-configs/{$chatConfig->id}";
        $plainTextPath = "$uploadDir/plainText/plainText.txt";

        if($request->isMethod('get')) {
            $plainText = Storage::get($plainTextPath);

            return view('knowledge', compact('chatConfig', 'plainText'));
        }

        $rules = [
            'type' => ['required', 'string', Rule::in(ChatConfig::TYPES)],
        ];

        if($request->input('type') === ChatConfig::TYPE_PDF) {
            $rules['file'] = 'file|mimes:pdf';
        } elseif ($request->input('type') === ChatConfig::TYPE_PLAIN_TEXT) {
            $rules['text'] = 'nullable|string';
        } else {
            $rules['q'] = 'nullable|array';
            $rules['a'] = 'nullable|array';
        }

        $request->validate($rules);
        $file = $request->file('file');

        if($request->input('type') === ChatConfig::TYPE_PDF) {
            $chatConfig->type = ChatConfig::TYPE_PDF;

            if($file) {
                $uploadDir = "chat-configs/{$chatConfig->id}/pdf";
                Storage::deleteDirectory($uploadDir);

                $pdf = (new \Smalot\PdfParser\Parser())->parseFile($file->getPathname());
                Storage::put($uploadDir.'/pdf.txt', $pdf->getText());
            }
        } elseif ($request->input('type') === ChatConfig::TYPE_PLAIN_TEXT) {
            $uploadDir = "chat-configs/{$chatConfig->id}/plainText";
            Storage::deleteDirectory($uploadDir);
            $chatConfig->type = ChatConfig::TYPE_PLAIN_TEXT;
            Storage::put($plainTextPath, $request->input('text', '') ?? '');
        } else {
            $chatConfig->items = ChatConfig::prepareItems($request->input('q', []), $request->input('a', []));
            $uploadDir = "chat-configs/{$chatConfig->id}/faq";
            Storage::deleteDirectory($uploadDir);
            $chatConfig->type = ChatConfig::TYPE_FAQ;

            if($chatConfig->items) {
                $faqText = '';

                foreach ($chatConfig->items as $item) {
                    $faqText .= $item['q']."\n";
                    $faqText .= $item['a']."\n\n";
                }

                Storage::put($uploadDir.'/faq.txt', $faqText);
            }
        }

        $chatConfig->save();

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('knowledge');
    }

    public function updateChatConfig(ChatConfigRequest $request)
    {
        /** @var ChatConfig $chatConfig */
        $data = $request->validated();
        $chatConfig = $request->user()->chatConfigLatest()->firstOrNew()->fill(\Arr::except($data, ['settings']));

        if($request->has('q')) {
            $chatConfig->items = ChatConfig::prepareItems($request->input('q', []), $request->input('a', []));
        }

        if($request->has('settings')) {
            $settings = [...($chatConfig->settings ?? []), ...$request->validated('settings', [])];
            $chatConfig->settings = $settings;
        }

        if(is_numeric($request->input('character')) && !is_numeric($chatConfig->getOriginal('character'))) {
            $chatConfig->custom_character = $chatConfig->getOriginal('character');
        }

        $chatConfig->save();

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        if($request->user()->getCurrentActiveSubscription()) {
            return redirect()->back()->with('success', 'Chat config updated.');
        }

        return \redirect()->route('pricing');
    }

    public function uploadCharacterImage(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:png,jpg,jpeg|dimensions:width=500,height=500']);
        $chatConfig = $request->user()->chatConfigLatest()->firstOrFail();

        $newName = $chatConfig->uuid.'.'.$request->file('file')->getClientOriginalExtension();

        $path = $request->file('file')->storePubliclyAs('public/chat-characters', $newName);

        if($path) {
            $path = substr($path, 7);

            $chatConfig->character = $path.'?'.\Str::random();

            $chatConfig->save();

            return redirect()->route('settings')->with('success', 'Character image uploaded.');
        }

        return redirect()->route('settings')->with('error', 'Failed to upload character image.');
    }
}
