
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['provider.index'], 'method' => 'delete']) }}
<div class="d-flex justify-content-end align-items-center">
        <a class="mr-2" href="#dd"><i class="far fa-eye text-secondary"></i></a>
</div>
{{ Form::close() }}
