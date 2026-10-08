{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}









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
                                        ACCOUNT RECOVERY
                                    </p>

                                    <h2 class="mt-3 text-4xl font-bold leading-tight">
                                        Get back into
                                        <span class="block text-indigo-400">
                                            your account.
                                        </span>
                                    </h2>

                                    <p class="mt-6 max-w-md text-sm leading-7 text-slate-400">
                                        Enter the email address associated with your
                                        account and we'll send you a secure link to
                                        create a new password.
                                    </p>

                                </div>


                                {{-- Information --}}
                                <div class="mt-10 space-y-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Secure password recovery
                                        </span>

                                    </div>


                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Reset link sent to your email
                                        </span>

                                    </div>


                                    <div class="flex items-center gap-3">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-800">
                                            <span class="text-sm text-indigo-400">✓</span>
                                        </div>

                                        <span class="text-sm text-slate-300">
                                            Your account remains protected
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


                            {{-- Header --}}
                            <div class="mb-8">

                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-indigo-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16.5 10.5V7a4.5 4.5 0 00-9 0v3.5m-1.5 0h12a1 1 0 011 1v7a1 1 0 01-1 1h-12a1 1 0 01-1-1v-7a1 1 0 011-1h12z"
                                        />
                                    </svg>

                                </div>

                                <h2 class="text-3xl font-bold text-slate-900">
                                    Forgot your password?
                                </h2>

                                <p class="mt-3 text-sm leading-6 text-slate-500">
                                    No problem. Enter the email address associated
                                    with your account and we'll send you a link to
                                    reset your password.
                                </p>

                            </div>


                            {{-- Session Status --}}
                            <x-auth-session-status
                                class="mb-5"
                                :status="session('status')"
                            />


                            {{-- Form --}}
                            <form
                                method="POST"
                                action="{{ route('password.email') }}"
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
                                        autocomplete="email"
                                        placeholder="Enter your email address"
                                        class="mt-2 block w-full rounded-lg border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />

                                    <x-input-error
                                        :messages="$errors->get('email')"
                                        class="mt-2"
                                    />

                                </div>


                                {{-- Submit --}}
                                <div class="mt-7">

                                    <button
                                        type="submit"
                                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >

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
                                                d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                                            />
                                        </svg>

                                        {{ __('Email Password Reset Link') }}

                                    </button>

                                </div>

                            </form>


                            {{-- Back to login --}}
                            <div class="mt-6 text-center">

                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                        />
                                    </svg>

                                    Back to sign in

                                </a>

                            </div>


                            {{-- Security notice --}}
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
                                            Secure password reset
                                        </p>

                                        <p class="mt-1 text-xs leading-5 text-slate-500">
                                            For your security, password reset instructions
                                            will only be sent to the email address registered
                                            with your account.
                                        </p>

                                    </div>

                                </div>

                            </div>


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