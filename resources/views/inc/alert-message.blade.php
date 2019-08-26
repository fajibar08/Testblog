
@if(session('alert_message'))
    <div class="alert alert-success" style="margin-top: 5px; padding: 8px;; !important;">
        {{session('alert_message')}}
    </div>
@endif