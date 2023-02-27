<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Subject Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_subject', 'Email Subject:') !!}
    {!! Form::text('email_subject', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Body Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_body', 'Email Body:') !!}
    {!! Form::text('email_body', null, ['class' => 'form-control']) !!}
</div>