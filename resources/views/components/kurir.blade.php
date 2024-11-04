@foreach ($ongkir as $key => $o)
<div class="accordion">
<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-{{++$key}}">
    <h4>{{strtoupper($o['code'])}}</h4>
</div>
<div class="accordion-body collapse" id="panel-body-{{$key}}" data-parent="#accordion">

    @foreach ($o['costs'] as $count=>$c)
    <div class="form-group">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" name="ongkir_select" id="{{$o['code']}}_{{++$count}}" value="{{$c['cost']['0']['value']}}" data-code="{{strtoupper($o['code'])}}" data-service="{{$c['service']}}" data-estimasi="{{substr(preg_replace('/\D/', '', $c['cost']['0']['etd']), -1)}}">
        <label class="custom-control-label" for="{{$o['code']}}_{{$count}}">({{$c['service']}}) {{$c['description']}}, Rp. {{number_format($c['cost']['0']['value'])}} ({{$c['cost']['0']['etd']}} @if($o['code'] != 'pos'){{' Hari'}}@endif) </label>
    </div>
    </div>                            
    @endforeach

</div>
</div>
@endforeach