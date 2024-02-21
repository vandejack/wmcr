@if (Session::has('alerts'))
  @foreach(Session::get('alerts') as $alert)
    <script type="text/javascript">
        $(function() {
          var alert_type = {!! json_encode($alert['type']) !!};
          var alert_text = {!! json_encode($alert['text']) !!};

          console.log(alert_type, alert_text);

          toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toastr-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          };

          toastr[alert_type](alert_text);
        });
    </script>
  @endforeach
@endif