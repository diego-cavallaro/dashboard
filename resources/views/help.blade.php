<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ url('images/logo/fsc.png') }}" />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <div>
            <a href="register" class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                <div>
                    <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>

                    <h2 class="mt-6 text-xl font-semibold text-gray-900">Documentación</h2>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                   
                    Lorem ipsum dolor sit amet consectetur adipiscing elit pulvinar mauris montes turpis, blandit nam est magna maecenas parturient per semper aptent tristique elementum, in purus porttitor habitasse ut congue condimentum augue netus ridiculus. Pellentesque ornare est ad sodales platea euismod morbi cursus ullamcorper sociosqu, eget mattis rutrum accumsan aenean class leo penatibus. Mattis iaculis tincidunt cum fringilla nisl dictum, per massa habitasse suspendisse tempus, a semper neque dui faucibus.

Mattis morbi aptent felis massa facilisi vehicula, sagittis mollis fermentum malesuada. Vulputate senectus sapien ad nibh tellus nunc mi, etiam erat mollis blandit faucibus natoque malesuada conubia, eu curabitur justo auctor sagittis pulvinar. Hendrerit habitant justo tortor morbi consequat ridiculus eget at, dignissim quis libero platea dis tincidunt aliquam, turpis vitae nibh auctor curae leo mus.
                    </p>
                </div>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                    </svg>
            </a>
            <br>
            <h2 class="text-lg font-medium text-center">Sistemas FSC S.A.</h2>


            
        </div>
    </x-authentication-card>
</x-guest-layout>