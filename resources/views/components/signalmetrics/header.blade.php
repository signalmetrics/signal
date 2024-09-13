<header class="px-5">
<div class="flex items-center justify-between h-12 mx-auto mt-5 rounded-lg max-w-7xl">
    <x-signalmetrics.shared.logo class="w-auto h-5 mr-3 text-black" />
    <div class="items-center justify-between flex-1 hidden w-full h-full mx-auto text-black md:flex max-w-7xl">
        <div class="flex items-center">
            <x-signalmetrics.header.site-dropdown class="hidden" />
            <x-signalmetrics.header.campaign-dropdown class="hidden" />
            <x-signalmetrics.header.date-range-dropdown />
            <p class="px-4 text-sm font-medium text-purple-500 animate-pulse"><strong>14</strong> People <span class="hidden lg:inline">on Your Site Right Now</span></p>
        </div>
        <div class="flex items-center h-full">
            <x-signalmetrics.header.button>
                Customize
            </x-signalmetrics.header.button>
            <x-signalmetrics.header.button>
                Share
            </x-signalmetrics.header.button>
            <x-signalmetrics.header.button>
                Settings
            </x-signalmetrics.header.button>
        </div>
    </div>
    <button class="flex items-center justify-center w-10 h-10 rounded-md md:hidden hover:bg-gray-100">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><path fill="none" d="M0 0h256v256H0z"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" d="M40 128h176M40 64h176M40 192h176"/></svg>
    </button>
</div>
</header>