<nav class="border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="navbar mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="navbar-start">
            <div class="btn btn-ghost btn-circle" tabindex="0" role="button">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </div>
        </div>
        <div class="navbar-center">
            <div class="flex shrink-0 items-center">
                <a href="{{ route('admin.dashboard') }}">
                    <x-utils.application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
            </div>
        </div>
        <div class="navbar-end">
            <x-utils.dark-mode-toggle />

            <x-actions.dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="focus:outline-hidden inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-black transition duration-150 ease-in-out hover:text-gray-700 dark:bg-gray-800 dark:text-white dark:hover:text-gray-300">
                        <div>{{ Auth::user()->name }}</div>

                        <div class="ms-1">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-actions.dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-actions.dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-actions.dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-actions.dropdown-link>
                    </form>
                </x-slot>
            </x-actions.dropdown>
        </div>
    </div>

</nav>
