<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        Admin Dashboard | Moriarty
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('') }}assets/img/logo.png">
    <link href="{{ asset('') }}assets/style.css" rel="stylesheet">
</head>

<!-- Mode Gelap Permanen dengan class "dark bg-gray-900" -->
<!-- Logika darkMode Alpine.js dihapus agar tidak bisa diubah-ubah -->
<body class="dark bg-gray-900" x-data="{ loaded: true, stickyMenu: false, sidebarToggle: false, scrollTop: false }">
    
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
            class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
            <!-- SIDEBAR HEADER -->
            <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
                class="flex items-center gap-2 pt-8 sidebar-header pb-7">
                
                <!-- Logo Moriarty Custom -->
                <a href="{{ url('/be/dashboard') }}" class="flex shrink-0 items-center gap-3 transition-opacity duration-300 hover:opacity-90">
                    <img src="{{ asset('') }}assets/img/logo.png" alt="Moriarty" class="h-9 w-9 object-contain">
                    <span class="text-xl font-bold tracking-tight text-white" :class="sidebarToggle ? 'hidden' : 'block'">
                        MORIARTY ETMINT
                    </span>
                </a>

            </div>
            <!-- SIDEBAR HEADER -->

            <div class="flex flex-col flex-1 overflow-y-auto duration-300 ease-linear no-scrollbar">
                <!-- Sidebar Menu (Diberi flex-1 dan min-h-full agar menu mengisi penuh layar) -->
                <nav class="flex flex-col flex-1 justify-between min-h-full">

                    <!-- Bagian Atas: Menu Group -->
                    <div>
                        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                            <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                                MENU
                            </span>
                        </h3>

                        <ul class="flex flex-col gap-2 mb-6">

                            <!-- Menu Item Dashboard -->
                            <li>
                                <a href="{{ url('/be/dashboard') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/dashboard*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/dashboard*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/dashboard*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Dashboard
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Profile / User -->
                            <li>
                                <a href="{{ url('/be/pengguna') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/pengguna*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/pengguna*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/pengguna*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        User
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Penawaran -->
                            <li>
                                <a href="{{ url('/be/penawaran') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/penawaran*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/penawaran*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.44 1.44 0 0 0 2.037 0l4.318-4.317a1.44 1.44 0 0 0 0-2.037L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/penawaran*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Penawaran
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Pembayaran -->
                            <li>
                                <a href="{{ url('be/pembayaran') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/pembayaran*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/pembayaran*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-5.25-9.75h16.5A2.25 2.25 0 0 1 21.75 9v9a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 18V9a2.25 2.25 0 0 1 2.25-2.25Z" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/pembayaran*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Pembayaran
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Pemesanan -->
                            <li>
                                <a href="{{ url('be/pemesanan') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/pemesanan*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/pemesanan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/pemesanan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Pemesanan
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Pencairan -->
                            <li>
                                <a href="{{ asset('be/pencairan') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/pencairan*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/pencairan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.717 12 12 12c-.717 0-1.536-.22-2.121-.659-1.172-.88-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/pencairan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Pencairan
                                    </span>
                                </a>
                            </li>

                            <!-- Menu Item Laporan -->
                            <li>
                                <a href="{{ asset('be/laporan') }}"
                                    class="menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg {{ request()->is('be/laporan*') ? 'menu-item-active !bg-gray-100 !text-brand-500 dark:!bg-gray-800' : 'menu-item-inactive text-gray-500 dark:text-gray-400' }}">
                                    <svg class="transition-colors duration-200 {{ request()->is('be/laporan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z" />
                                    </svg>
                                    <span class="menu-item-text transition-colors duration-200 {{ request()->is('be/laporan*') ? '!text-brand-500' : 'group-hover:!text-brand-500' }}" :class="sidebarToggle ? 'lg:hidden' : ''">
                                        Laporan
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Bagian Bawah: Menu Item Log Out Mentok Bawah (KARENA flex-1 di kontainer dan mt-auto) -->
                    <div class="mt-auto pb-4">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                           <button type="submit"
                                class="w-full text-left flex items-center gap-3 px-4 py-2.5 menu-item group hover:!bg-gray-100 hover:!text-brand-500 dark:hover:!bg-gray-800 dark:hover:!text-brand-500 transition-colors duration-200 rounded-lg menu-item-inactive text-gray-500 dark:text-gray-400">
                                <svg class="transition-colors duration-200 group-hover:!text-brand-500"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                                <span class="menu-item-text transition-colors duration-200 group-hover:!text-brand-500" :class="sidebarToggle ? 'lg:hidden' : ''">
                                    Log Out
                                </span>
                            </button>
                        </form>
                    </div>

                </nav>
            </div>
        </aside>
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
                class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            <header x-data="{ menuToggle: false }"
                class="sticky top-0  flex w-full border-gray-200 bg-white lg:border-b dark:border-gray-800 dark:bg-gray-900">
                <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
                    <div
                        class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4 dark:border-gray-800">
                        
                        <!-- Hamburger Toggle BTN -->
                        <button
                            :class="sidebarToggle ? 'lg:bg-transparent dark:lg:bg-transparent bg-gray-100 dark:bg-gray-800' : ''"
                            class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg border-gray-200 text-gray-500 lg:h-11 lg:w-11 lg:border dark:border-gray-800 dark:text-gray-400"
                            @click.stop="sidebarToggle = !sidebarToggle">
                            <svg class="hidden fill-current lg:block" width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="" />
                            </svg>
                            <svg :class="sidebarToggle ? 'hidden' : 'block lg:hidden'" class="fill-current lg:hidden" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 6C3.25 5.58579 3.58579 5.25 4 5.25L20 5.25C20.4142 5.25 20.75 5.58579 20.75 6C20.75 6.41421 20.4142 6.75 20 6.75L4 6.75C3.58579 6.75 3.25 6.41422 3.25 6ZM3.25 18C3.25 17.5858 3.58579 17.25 4 17.25L20 17.25C20.4142 17.25 20.75 17.5858 20.75 18C20.75 18.4142 20.4142 18.75 20 18.75L4 18.75C3.58579 18.75 3.25 18.4142 3.25 18ZM4 11.25C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75L12 12.75C12.4142 12.75 12.75 12.4142 12.75 12C12.75 11.5858 12.4142 11.25 12 11.25L4 11.25Z" fill="" />
                            </svg>
                            <!-- cross icon -->
                            <svg :class="sidebarToggle ? 'block lg:hidden' : 'hidden'" class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z" fill="" />
                            </svg>
                        </button>
                        <!-- Hamburger Toggle BTN -->

                        <!-- Logo Mobile Moriarty Custom -->
                        <a href="{{ url('/be/dashboard') }}" class="flex shrink-0 items-center gap-2 lg:hidden">
                            <img src="{{ asset('') }}assets/img/logo.png" alt="Moriarty" class="h-8 w-8 object-contain">
                        </a>

                        <!-- Application nav menu button -->
                        <button
                            class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 lg:hidden dark:text-gray-400 dark:hover:bg-gray-800"
                            :class="menuToggle ? 'bg-gray-100 dark:bg-gray-800' : ''"
                            @click.stop="menuToggle = !menuToggle">
                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.4951C6.82745 10.4951 7.49902 11.1667 7.49902 11.9951V12.0051C7.49902 12.8335 6.82745 13.5051 5.99902 13.5051C5.1706 13.5051 4.49902 12.8335 4.49902 12.0051V11.9951C4.49902 11.1667 5.1706 10.4951 5.99902 10.4951ZM17.999 10.4951C18.8275 10.4951 19.499 11.1667 19.499 11.9951V12.0051C19.499 12.8335 18.8275 13.5051 17.999 13.5051C17.1706 13.5051 16.499 12.8335 16.499 12.0051V11.9951C16.499 11.1667 17.1706 10.4951 17.999 10.4951ZM13.499 11.9951C13.499 11.1667 12.8275 10.4951 11.999 10.4951C11.1706 10.4951 10.499 11.1667 10.499 11.9951V12.0051C10.499 12.8335 11.1706 13.5051 11.999 13.5051C12.8275 13.5051 13.499 12.8335 13.499 12.0051V11.9951Z" fill="" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                @yield('content')
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->
    @stack('modals')
</body>

</html>