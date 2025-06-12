<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @php
                        $role = Auth::user()->role;
                    @endphp

                    @if ($role === 'admin')
                        <h3>Welcome, Admin!</h3>
                        <p>Here’s your admin dashboard content.</p>
                    @elseif ($role === 'academic_head')
                        <h3>Welcome, Academic Head!</h3>
                        <p>Here’s your academic head dashboard content.</p>
                    @elseif ($role === 'academic_staff')
                        <h3>Welcome, Academic Staff!</h3>
                        <p>Here’s your academic staff dashboard content.</p>
                    @else
                        <h3>Welcome!</h3>
                        <p>You are logged in, but no dashboard role is assigned.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
