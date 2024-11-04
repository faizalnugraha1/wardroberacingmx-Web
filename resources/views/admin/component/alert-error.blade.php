@if($errors->any())
@foreach($errors->getMessages() as $this_error)
<div class="alert alert-danger" role="alert">
    <i class="fas fa-exclamation-triangle  mr-3"></i> {{$this_error[0]}}
</div> 
@endforeach
@endif  