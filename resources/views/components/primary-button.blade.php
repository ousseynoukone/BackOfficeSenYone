<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-dark-800 dark:bg-dark-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-dark-800 uppercase tracking-widest hover:bg-blue-900 dark:hover:bg-dark focus:bg-dark-900 dark:focus:bg-dark active:bg-dark-900 dark:active:bg-dark-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-dark-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
