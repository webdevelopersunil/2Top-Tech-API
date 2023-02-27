<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $emailTemplate->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $emailTemplate->name }}</p>
</div>

<!-- Email Subject Field -->
<div class="col-sm-12">
    {!! Form::label('email_subject', 'Email Subject:') !!}
    <p>{{ $emailTemplate->email_subject }}</p>
</div>

<!-- Email Body Field -->
<div class="col-sm-12">
    {!! Form::label('email_body', 'Email Body:') !!}
    <p>{{ $emailTemplate->email_body }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $emailTemplate->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $emailTemplate->updated_at }}</p>
</div>

