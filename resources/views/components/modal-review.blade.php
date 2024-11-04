<div id="review_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Review Produk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
        <form action="{{ route('user.review.store') }}" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate="" >                        
            @csrf
            @method('POST')
            <input type="hidden" name="id" value="{{$data->enc_id()}}">
          <div class="modal-body">
            <h5>{{$data->barang->nama}}</h5>

            <div class="owl-carousel owl-theme" style="max-height: 300px">         
                <div class="item d-flex justify-content-center">
                    <img class="w-auto h-100" style="max-height: 250px" src="{{ asset('images/'.$data->barang->thumbnail) }}" alt="thumbnail">
                </div> 
                @foreach ($data->barang->images as $key=> $bi)
                <div class="item d-flex justify-content-center">
                    <img class="w-auto h-100" style="max-height: 250px" src="{{ asset('images/'.$bi->file) }}" alt="images{{++$key}}">
                </div>                          
                @endforeach 
            </div>
            <div class="container d-flex justify-content-center">
                <div class="row">
                    <div class="col-12">
                        <div class="stars form-group">                
                            <input class="form-control star star-5" id="star-5" type="radio" name="rating" value="5" required/>
                            <label class="star star-5 mb-0" for="star-5"></label>
                            <input class="form-control star star-4" id="star-4" type="radio" name="rating" value="4"/>
                            <label class="star star-4 mb-0" for="star-4"></label>
                            <input class="form-control star star-3" id="star-3" type="radio" name="rating" value="3"/>
                            <label class="star star-3 mb-0" for="star-3"></label>
                            <input class="form-control star star-2" id="star-2" type="radio" name="rating" value="2"/>
                            <label class="star star-2 mb-0" for="star-2"></label>
                            <input class="form-control star star-1" id="star-1" type="radio" name="rating" value="1"/>
                            <label class="star star-1 mb-0" for="star-1"></label>
                            <div class="invalid-feedback text-center">
                                Rating harus diisi
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Review</label>
                <textarea name="review" class="form-control auto-height" rows="4"  style="min-height: 108px"></textarea>
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>

        </div>
    </div>
</div> 