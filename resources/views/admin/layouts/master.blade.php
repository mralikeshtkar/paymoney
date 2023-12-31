@php
    $app_name_long   = settings('name');
    $company_logo   = settings('logo');
    if (trim($app_name_long) && strpos($app_name_long, ' ') !== false)
    {
        $word = explode(' ',$app_name_long);
        $app_name_short = ucfirst($word[0][0]).ucfirst($word[1][0]);
    }
    else
    {
        $app_name_short = ucfirst($app_name_long[0]);
    }

    if(!empty(Auth::guard('admin')->user()->picture))
    {
      $picture = Auth::guard('admin')->user()->picture;
      $admin_image = asset('uploads/userPic/'.$picture);
    }
    else
    {
      $admin_image = asset('uploads/userPic/default-image.png');
    }
    $admin_name = Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name;
    $admin_email = Auth::guard('admin')->user()->email;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="MTS">
        <title> {{ $app_name_long }} | @yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <meta name="csrf-token" content="{{ csrf_token() }}"><!-- for ajax -->

        <script type="text/javascript">
            var SITE_URL = "{{url('/')}}";
            var ADMIN_PREFIX = "{{ \Config::get('adminPrefix') }}";
            var ADMIN_URL = SITE_URL + '/' + ADMIN_PREFIX;
            var FIATDP = "<?php echo number_format(0, preference('decimal_format_amount', 2)); ?>";
            var CRYPTODP = "<?php echo number_format(0, preference('decimal_format_amount_crypto', 8)); ?>";
        </script>

        <!---favicon-->
        <link rel="shortcut icon" href="{{ faviconPath() }}" />

        @include('admin.layouts.partials.head_style')
        @include('admin.layouts.partials.head_script')

    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper_custom">
            @include('admin.layouts.partials.header')

            <!-- sidebar -->
            <aside class="main-sidebar">
                <section class="sidebar">
                    @include('admin.layouts.partials.sidebar_menu')
                </section>
            </aside>

            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    @yield('page_content')
                </section>
            </div>

            <!-- footer -->
            <footer class="main-footer">
                @include('admin.layouts.partials.footer')
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>

        <!-- body_script -->
        @include('admin.layouts.partials.body_script')
        @stack('extra_body_scripts')
    </body>
</html>
