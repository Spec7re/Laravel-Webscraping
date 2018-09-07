
<table id="rates" border="1">

    <tr>
        <th>Period</th>
        @foreach($euriborDates as $date)
            <th>{{ date_format($date->for_date, 'd-m-Y') }}</th>
        @endforeach
    </tr>

    @for($i = 0; $i < $euriborDates[0]->rates->count(); $i++)

        <tr>
            <td>{{ $euriborDates[0]->rates[$i]->duration }}</td>

            @foreach($euriborDates as $date)
                <td>{{ $date->rates[$i]->rate }} %</td>
            @endforeach

        </tr>
    @endfor


</table>


<button type="button"><a href="http://euribor.local/">UPDATE</a></button>

