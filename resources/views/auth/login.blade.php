{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
 --}}







 



 <x-guest-layout>

    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-5xl">

            <div class="overflow-hidden rounded-2xl bg-white shadow-xl border border-slate-200">

                <div class="grid lg:grid-cols-2">

                    {{-- ====================================================== --}}
                    {{-- LEFT SIDE --}}
                    {{-- ====================================================== --}}
                    <div class="hidden lg:flex bg-slate-900 p-10 xl:p-12 text-white">

                        <div class="flex w-full flex-col justify-between">

                            <div>

                                {{-- Logo --}}
                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-600">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h12m-9 4a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <h1 class="text-lg font-semibold">
                                            POS System
                                        </h1>

                                        <p class="text-sm text-slate-400">
                                            Point of Sale Management
                                        </p>

                                    </div>

                                </div>


                                {{-- Main content --}}
                                <div class="mt-24">

                                    <p class="text-sm font-medium text-indigo-400">
                                        BUSINESS MANAGEMENT
                                    </p>

                                    <h2 class="mt-3 text-4xl font-bold leading-tight">
                                        Everything you need
                                        <span class="block text-indigo-400">
                                            to run your business.
                                        </span>
                                    </h2>

                                    <p class="mt-6 max-w-md text-sm leading-7 text-slate-400">
                                        Manage your sales, inventory, purchasing,
                                        customers, payments and reports from a
                                        single centralized platform.
                                    </p>

                                </div>


                                {{-- Features --}}
                                <div class="mt-10 space-y-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Sales and payment management
                                        </span>

                                    </div>


                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Inventory and purchasing
                                        </span>

                                    </div>


                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Reports and business insights
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- Footer --}}
                            <div class="mt-12 border-t border-slate-800 pt-5">

                                <p class="text-xs text-slate-500">
                                    &copy; {{ date('Y') }} POS System. All rights reserved.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ====================================================== --}}
                    {{-- RIGHT SIDE --}}
                    {{-- ====================================================== --}}
                    <div class="flex items-center bg-white px-6 py-10 sm:px-10 lg:px-12 xl:px-16">

                        <div class="w-full max-w-md mx-auto">


                            {{-- Mobile Logo --}}
                            <div class="mb-8 flex items-center gap-3 lg:hidden">

                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h12m-9 4a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <h1 class="font-semibold text-slate-900">
                                        POS System
                                    </h1>

                                    <p class="text-xs text-slate-500">
                                        Point of Sale Management
                                    </p>

                                </div>

                            </div>


                            {{-- Heading --}}
                            <div class="mb-8">

                                <h2 class="text-3xl font-bold text-slate-900">
                                    Sign in
                                </h2>

                                <p class="mt-2 text-sm text-slate-500">
                                    Enter your account details to continue.
                                </p>

                            </div>


                            {{-- Session Status --}}
                            <x-auth-session-status
                                class="mb-5"
                                :status="session('status')"
                            />


                            {{-- Login Form --}}
                            <form
                                method="POST"
                                action="{{ route('login') }}"
                            >
                                @csrf


                                {{-- Email --}}
                                <div>

                                    <x-input-label
                                        for="email"
                                        :value="__('Email Address')"
                                        class="text-sm font-medium text-slate-700"
                                    />

                                    <x-text-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        :value="old('email')"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Enter your email address"
                                        class="mt-2 block w-full rounded-lg border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />

                                    <x-input-error
                                        :messages="$errors->get('email')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Password --}}
                                <div class="mt-5">

                                    <div class="flex items-center justify-between">

                                        <x-input-label
                                            for="password"
                                            :value="__('Password')"
                                            class="text-sm font-medium text-slate-700"
                                        />

                                        @if (Route::has('password.request'))

                                            <a
                                                href="{{ route('password.request') }}"
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                            >
                                                Forgot password?
                                            </a>

                                        @endif

                                    </div>


                                    <x-text-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        class="mt-2 block w-full rounded-lg border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />

                                    <x-input-error
                                        :messages="$errors->get('password')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Remember --}}
                                <div class="mt-5">

                                    <label class="inline-flex items-center">

                                        <input
                                            id="remember_me"
                                            type="checkbox"
                                            name="remember"
                                            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        >

                                        <span class="ms-2 text-sm text-slate-600">
                                            Remember me
                                        </span>

                                    </label>

                                </div>


                                {{-- Login Button --}}
                                <div class="mt-7">

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Sign in
                                    </button>

                                </div>

                            </form>


                            {{-- Security --}}
                            <div class="mt-8 rounded-lg border border-slate-200 bg-slate-50 p-4">

                                <div class="flex gap-3">

                                    <div class="mt-0.5 text-emerald-600">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 15v2m-6 3h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3h8z"
                                            />
                                        </svg>
                                    </div>

                                    <div>

                                        <p class="text-sm font-medium text-slate-700">
                                            Secure access
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-slate-500">
                                            Your account and business data are protected
                                            by secure authentication and access controls.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Bottom text --}}
                            <p class="mt-8 text-center text-xs text-slate-400">
                                Authorized users only
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>