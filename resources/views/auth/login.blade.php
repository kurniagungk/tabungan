<x-layout>

    <div class="min-h-screen flex items-center justify-center bg-base-200 -mt-30">
        <div class="w-full max-w-4xl grid grid-cols-1 ">
            <!-- Left (kosong atau bisa isi hero/image) -->

            <!-- Right (form login) -->
            <div class="flex items-center justify-center ">
                <div class="w-full max-w-sm">



                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">E-mail</legend>
                            <input type="text" class="input w-full @error('email') input-error @enderror"
                                type="email" name="email" class="grow" placeholder="your@email.com" required
                                autofocus />

                            @error('email')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror

                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Password</legend>
                            <input type="password" class="input w-full" type="password" name="password" class="grow"
                                required />

                        </fieldset>


                        <hr>


                        <div class="flex justify-end">
                            <button class="btn btn-primary ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m0 0l-4-4m4 4l-4 4" />
                                </svg>
                                Login
                            </button>
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</x-layout>
