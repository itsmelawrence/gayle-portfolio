<div class="mock t-{{ $project['tone'] }}" aria-hidden="true">@switch($project['mock'])
@case('ad')<div class="m-ad"><i class="m-av"></i><i class="m-l s"></i><i class="m-img"></i><i class="m-l"></i><i class="m-btn"></i></div>@break
@case('grid')<div class="m-grid">{!! str_repeat('<i></i>', 9) !!}</div>@break
@case('form')<div class="m-form"><i class="m-l s"></i><i class="m-in"></i><i class="m-in"></i><i class="m-in"></i><i class="m-btn"></i></div>@break
@case('phone')<div class="m-phone"><i class="m-play"></i></div>@break
@case('feed')<div class="m-feed"><i></i><i></i><i></i><i></i></div>@break
@case('browser')<div class="m-browser"><b><i></i><i></i><i></i></b><i class="m-hero"></i><i class="m-l"></i><i class="m-l s"></i></div>@break
@case('flow')<div class="m-flow"><i class="n"></i><i class="e"></i><i class="n"></i><i class="e"></i><i class="n"></i></div>@break
@endswitch</div>
