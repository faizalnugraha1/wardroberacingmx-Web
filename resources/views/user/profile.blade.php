@extends('layouts.app')

@section('csslib')
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row px-xl-5">
        @include('components.user-sidebar')      


        <div class="col-lg-9 col-md-8">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Profile</span></h5>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="text-muted">Ubah informasi umum akun</p>
                    <form action="{{ route('setting.general') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row align-items-center">
                            <label for="site-title" class="form-control-label col-sm-3 text-md-right">Nama Lengkap</label>
                            <div class="col-sm-6 col-md-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="name" name="name" class="form-control" required="" value="{{Auth::user()->name}}">
                                </div>                        
                            </div>                     
                        </div>
    
                        <div class="form-group row align-items-center">
                        <label for="site-title" class="form-control-label col-sm-3 text-md-right">Email</label>
                        <div class="col-sm-6 col-md-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-at"></i>
                                </div>
                            </div>
                            <input type="email" class="form-control" disabled value="{{Auth::user()->email}}">
                            </div>                     
                        </div>                     
                    </div>
    
                    <div class="form-group row align-items-center">
                        <label for="site-title" class="form-control-label col-sm-3 text-md-right">No. Hp</label>
                        <div class="col-sm-6 col-md-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <input type="text" name="no_hp" class="form-control phone-number" value="{{Auth::user()->no_hp}}">
                        </div>               
                    </div>                     
                </div>
                
                <div class="text-md-right">
                    <button class="save btn btn-success" data-url="{{ route('validatepass') }}">Simpan</button>
                </div>

                </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body" id="card_alamat">
                    <p class="text-muted">Alamat</p>
                    @csrf
                    @foreach (Auth::user()->alamat as $key => $a)
                    <div class="form-group row align-items-center">
                        <label for="site-title" class="form-control-label col-sm-2 text-md-right">Alamat #{{++$key}}</label>
                        <div class="col-sm-10 col-md-10">
                            <div class="media align-items-center">
                                <div class="media-body border p-2">
                                  <p class="mb-0">{{$a->full()}}</p>
                                </div>
                                <div class="btn-list justify-content-center ml-2" role="group" data-id="{{$a->enc_id()}}">
                                    <button class="edit btn btn-icon btn-warning" data-url="{{ route('user.edit.alamat') }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="delete btn btn-icon btn-danger" data-url="{{ route('user.delete.alamat') }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>                        
                        </div>                     
                    </div>                        
                    @endforeach

                    <div class="text-md-right mt-5">
                        <button id="btn_new_alamat" class="btn btn-success" data-url="{{ route('user.create.alamat') }}">Tambah</button>
                    </div>

                </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <p class="text-muted">Ubah Password akun</p>
                    <form action="{{ route('setting.password') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row align-items-center">
                        <label for="site-title" class="form-control-label col-sm-3 text-md-right">Password</label>
                        <div class="col-sm-6 col-md-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </div>
                                </div>
                                <input type="password" name="password" class="form-control pwstrength" data-indicator="pwindicator" required="">
                            </div>                    
                        </div>                     
                    </div>     
                    
                    <div class="form-group row align-items-center">
                        <label for="site-title" class="form-control-label col-sm-3 text-md-right">Password Confirmation</label>
                        <div class="col-sm-6 col-md-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </div>
                                </div>
                                <input type="password" name="password_confirmation" class="form-control pwstrength" data-indicator="pwindicator" required="">
                            </div>                    
                        </div>                     
                    </div>
                    
                    <div class="text-md-right">
                        <button class="save btn btn-success" data-url="{{ route('validatepass') }}">Simpan</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('modal')
    <div id="create_modal">    
       
    </div>

    <div id="edit_modal">        
    </div>
@endsection

@section('script_line')
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>

<script>
$('.save').click(function(event) {
    event.preventDefault();
    var url = $(this).data('url');
    var form = $(this).closest('form');
    console.log(form);
    Swal.fire({
        title: 'Enter your password',
        input: 'password',
        inputLabel: 'Password',
        inputPlaceholder: 'Enter your password',
        inputAttributes: {
            autocapitalize: 'off',
            autocorrect: 'off'
        }
    }).then((data) => {
        if (data.value){
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    password: data.value
                },
                dataType: "json",
                success: function (response) {
                    if(response.result == 'Accepted'){
                        form.submit();
                    } else if (response.result == 'Refused'){
                        Swal.fire({
                            title: 'Password salah',
                            text: 'Password yang anda masukkan salah',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        }
    });
});

$('#btn_new_alamat').click(function (e) { 
    e.preventDefault();
    var url = $(this).data('url');
    // alert(url);
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
            if (response.status == 'info')
            {
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'Ok'
                });
            } else if(response.status == 'allowed')
            {
                $('#create_modal').html(response.modal);
                $('#create_form').modal('show');
            }
        }
    });
});

$('.delete').click(function (e) { 
    e.preventDefault();
    var id = $(this).closest('.btn-list').data('id');
    var url = $(this).data('url');
    var token = $('#card_alamat').find('input[name=_token]').val();
    // alert(id);
    Swal.fire({
        title: `Hapus alamat yang dipilih?`,
        text: "Jika data ini dihapus maka tidak dapat dikembalikan lagi.",
        icon: "warning",
        confirmButtonText: 'Ya, Hapus',
    }).then((result) => {
        if (result.isConfirmed) {
            // alert(url + id + token);
            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    id: id,
                    method: 'delete',
                    _token: token
                },
                dataType: "json",
                success: function (rest) {
                    if (rest.status == 'success')
                    {
                        location.reload();
                    }
                }
            });
        }
    });
});

$('.edit').click(function (e) { 
    e.preventDefault();
    var id = $(this).closest('.btn-list').data('id');
    var url = $(this).data('url');
    // alert(url + id);
    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: id
        },
        dataType: "json",
        success: function (response) {
            if(response.status == 'success')
            {
                $('#edit_modal').html(response.modal);
                $('#edit_form').modal('show');
            }
        }
    });
});

</script>
@endsection