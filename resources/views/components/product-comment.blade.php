@if (count($review) > 0)
@foreach ($review as $r)
<div class="media mb-4">
    <div class="media-body">
        <h6>{{$r->invoice->user->name}}<small> - <i>{{ \Carbon\Carbon::parse($r->created_at)->translatedFormat('d F Y') }}</i></small></h6>
        <div class="text-primary mb-2">
            <?php $sisa = round(($r->rating - floor($r->rating))*10); ?>
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= floor($r->rating))
                <i class="fa fa-star"></i>      
                @elseif ($i == (floor($r->rating) + 1))   
                    @if ($sisa == 0)
                    <i class="far fa-star"></i>      
                    @elseif ($sisa > 0 && $sisa < 4)
                    <i class="far fa-star"></i>    
                    @elseif ($sisa >= 4 && $sisa <= 6)
                    <i class="fa fa-star-half-alt"></i>
                    @elseif ($sisa > 6)
                    <i class="fa fa-star"></i>
                    @endif               
                @else                    
                <i class="far fa-star"></i>      
                @endif
            @endfor
        </div>
        @if ($r->review)
        <p>{{$r->review}}</p>                                                
        @endif
    </div>
</div>    

{{$review->links('vendor.pagination.comment-pagination')}}
@endforeach
@else
<div class="w-100 text-center">
    <h5 class="text-muted"><em>Belum ada review</em></h5>
</div>                                                                
@endif