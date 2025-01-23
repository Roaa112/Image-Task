@include('User.head')
  <body>
  @include('User.scroller')
      <!-- partial:partials/_sidebar.html -->
    
      @include('User.sidebar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        @include('User.navbar')
        <!-- partial -->
        @yield('body')
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
@include('User.footer')