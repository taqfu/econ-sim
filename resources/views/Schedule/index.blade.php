<?php use App\Schedule;

?>
@extends('layouts.app')

@section('content')
    <form method="POST" action="{{route('schedule.store')}}">
        {{csrf_field()}}
    <table class='table'>
        <tr><td></td>
        @for($hour=0; $hour<24; $hour++)
            <td>{{$hour}}</td>
        @endfor
      </tr><tr><td>Sleep ({{$avatar->sleep_req}}h req.)</td>
        @for($hour=0; $hour<24; $hour++)
            <td class='schedule-cell
            @if (Schedule::fetch_type($hour)==0)
                info
            @endif
            '>
                <input type='radio' name='{{$hour}}' value="0"
                @if (Schedule::fetch_type($hour)==0)
                    checked
                @endif
                />
            </td>
        @endfor
      </tr><tr><td>Work</td>
        @for($hour=0; $hour<24; $hour++)
            <td class='schedule-cell
            @if (Schedule::fetch_type($hour)==1)
                info
            @endif
            '>
                <input type='radio' name='{{$hour}}' value="1"
                @if (Schedule::fetch_type($hour)==1)
                    checked
                @endif
                />
            </td>
        @endfor
      </tr><tr><td>Anything</td>
        @for($hour=0; $hour<24; $hour++)
            <td class='schedule-cell
            @if (Schedule::fetch_type($hour)==2)
                info
            @endif
            '>
                <input type='radio' name='{{$hour}}' value="2"
                @if (Schedule::fetch_type($hour)==2)
                    checked
                @endif
                />
            </td>
        @endfor
      </tr>
    </table>
        <input type='submit' class='btn' value='Update'/>
    </form>
@endsection