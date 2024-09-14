<div 
    x-data="{
        theme: 'light',
        toggle() {
            if(this.theme == 'dark'){ 
                this.theme = 'light';
                localStorage.setItem('theme', 'light');
            }else{ 
                this.theme = 'dark';
                localStorage.setItem('theme', 'dark');
            }
        }
    }"
    x-init="
        console.log('hey');
        console.log(localStorage.getItem('theme'));
        theme = localStorage.getItem('theme');
        if(document.documentElement.classList.contains('dark')){ theme='dark'; }
        $watch('theme', function(value){
            if(value == 'dark'){
                document.documentElement.classList.add('dark');
        } else {
                document.documentElement.classList.remove('dark');
            }
        })
    "
    x-on:click="toggle()"
    class="flex items-center pr-2.5 pl-2 py-1.5 text-xs rounded-md cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-zinc-800"
    x-cloak>

    <input type="hidden" name="toggleDarkMode" :value="theme">
 
    <button
        x-ref="toggle"
        type="button"
        role="switch"
        :aria-checked="theme == 'dark'"
        :aria-labelledby="$id('toggle-label')"
        :class="(theme == 'dark') ? 'bg-gray-700' : 'bg-slate-300'"
        class="relative inline-flex flex-shrink-0 py-1 rounded-full w-7 focus:ring-0"
    x-cloak>
        <span
            :class="(theme == 'dark') ? 'translate-x-[13px]' : 'translate-x-1'"
            class="w-3 h-3 bg-white rounded-full shadow-md focus:outline-none"
            aria-hidden="true"
        ></span>
    </button>

    <label
        :id="$id('toggle-label')"
        :class="{ 'text-gray-600' : theme == 'light' || theme == null, 'text-gray-300' : theme == 'dark'  }"
        class="flex-shrink-0 ml-1.5 mr-0.5 font-medium cursor-pointer"
    x-cloak>
        <span x-show="(theme == 'light' || theme == null)">Dark Mode</span>
        <span x-show="(theme == 'dark')">Light Mode</span>
    </label>

</div>
            {{-- <x-signalmetrics.header.button>
                <svg class="w-3.5 h-3.5 my-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><line x1="128" y1="36" x2="128" y2="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><circle cx="128" cy="128" r="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="60" y1="60" x2="48" y2="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="60" y1="196" x2="48" y2="208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="196" y1="60" x2="208" y2="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="196" y1="196" x2="208" y2="208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="36" y1="128" x2="20" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="128" y1="220" x2="128" y2="236" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/><line x1="220" y1="128" x2="236" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/></svg>
            </x-signalmetrics.header.button> --}}