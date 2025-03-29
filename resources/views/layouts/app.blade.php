<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Prevent flash of light mode -->
    <script>
        // Check for saved theme preference or prefer-color-scheme
        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

    <!-- datatables css-->
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.2.2/r-3.0.4/sp-2.3.3/sr-1.4.1/datatables.min.css" rel="stylesheet" integrity="sha384-uMRVFAehEmeRx+eu65ZAwUtvyFbGSAXOA+y0/bktyqsYwlw8575VE7T7o5PqC9HY" crossorigin="anonymous">

    <!-- select picker css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/erimicel/select2-tailwindcss-theme/dist/select2-tailwindcss-theme-plain.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- datatables center -->
<style>
    #datatables th,
    #datatables td {
        text-align: center;
    }
</style>

<!-- Page styles -->
@isset($style)
    {{ $style }}
@endisset

<body class="font-sans antialiased">
    <!-- Toast container -->
    <div id="toast-container" class="toast toast-end toast-top z-50"></div>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

<!-- datatables script -->
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.2.2/r-3.0.4/sp-2.3.3/sr-1.4.1/datatables.min.js" integrity="sha384-EyOrkIw2BJ0wGDDncNwhfC5UwkD+tjKPyPNUqOd9J92FC+Y3JT5Q/32Ad5/x0ylC" crossorigin="anonymous"></script>

<!-- select picker -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Toast Script -->
<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const alert = document.createElement('div');

        // Thêm classes animation
        alert.className =
            `alert ${type === 'success' ? 'alert-success' : 'alert-error'} shadow-lg transform translate-x-full opacity-0 transition-all duration-300 ease-in-out`;

        alert.innerHTML = `
                <div>
                    <span>${message}</span>
                </div>
                <div class="flex-none">
                    <button onclick="closeToast(this.parentElement.parentElement)" class="btn btn-sm btn-ghost">✕</button>
                </div>
            `;

        container.appendChild(alert);

        // Trigger animation vào
        setTimeout(() => {
            alert.classList.remove('translate-x-full', 'opacity-0');
        }, 100);

        // Tự động đóng sau 5s
        setTimeout(() => {
            closeToast(alert);
        }, 5000);
    }

    function closeToast(element) {
        // Animation khi ẩn
        element.classList.add('translate-x-full', 'opacity-0');

        // Xóa element sau khi animation hoàn thành
        setTimeout(() => {
            element.remove();
        }, 300);
    }

    @if (session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if (session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif
</script>

<!-- page script -->
@isset($script)
    {{ $script }}
@endisset

<!-- Dark Mode Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the toggle button
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Function to show the correct icon based on the theme
        function updateIcon(isDark) {
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.add('hidden');

            if (isDark) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }

        // Initial setup based on previous settings
        const isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Update the icon to match current theme
        updateIcon(isDarkMode);

        // Add click event for the toggle button
        themeToggleBtn.addEventListener('click', function() {
            // If dark mode is active
            const isDark = document.documentElement.classList.contains('dark');

            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }

            // Update icon to match new theme (opposite of previous state)
            updateIcon(!isDark);
        });
    });
</script>

<!-- Select2 Initialization -->
<script>
    $(function() {
        $('.select-search').each(function() {
            let options = {
                theme: 'tailwindcss-3',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-full') ?
                    '100%' : 'style',
                placeholder: $(this).data('placeholder') || 'Chọn một lựa chọn',
                allowClear: Boolean($(this).data('allow-clear')),
                closeOnSelect: !$(this).attr('multiple'),
                tags: Boolean($(this).data('tags')),
            };

            $(this).select2(options);
        });
    });
</script>

<!-- datatables initialization -->
<script>
    $(document).ready(function() {
        $('#datatables').DataTable({
            language: {
                "entries per page": "số bản ghi mỗi trang",
                "search": "Tìm kiếm",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "emptyTable": "Không có dữ liệu",
                "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                paginate: {
                    "first": "",
                    "last": "",
                    "next": "Tiếp theo",
                    "previous": "Trước đó"
                }
            }
        });
    });
</script>

</html>
