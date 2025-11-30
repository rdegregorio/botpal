<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Chat config
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Update general prompt and upload a document you will chat about.
                            </p>
                        </header>

                        <form method="post" action="{{ route('dashboard.update-chat-config') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="prompt" value="General prompt" />
                                <textarea id="prompt" name="general_prompt" class="mt-1 block w-full"
                                          required>{{$chatConfig?->general_prompt}}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <x-input-label for="file" value="File"/>
                            <input @if(!$chatConfig) required @endif type="file" name="file" id="file" accept=".pdf,.doc,.docx">
                            <x-input-error class="mt-2" :messages="$errors->get('file')"/>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </form>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
