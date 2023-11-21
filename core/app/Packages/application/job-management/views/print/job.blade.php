<style type="text/css">

    td {
        font-weight: 400;
        font-size: 10px;
    }

    th {
        text-align: left;
    }

    .border-all {
        border-left-style: solid;
        border-width: 1px;

        border-right-style: solid;
        border-width: 1px;

        border-top-style: solid;
        border-width: 1px;

        border-bottom-style: solid;
        border-width: 1px;
    }
</style>
<table width="100%" style="padding-bottom: 10px">
    <tbody>
    {{--<tr>
        <td colspan="3" align="left"><img src="{{asset('assets/images/logo-dark.png')}}"
                                          style="width: 150px;height: 50px"></td>
        <td></td>
        <td colspan="3" align="right"><img src="{{asset('assets/images/logo-nilakroo.png')}}"
                                           style="width: 150px;height: 50px"></td>
    </tr>--}}
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr><!--row1-->
        <td colspan="3">
            Date:
            <table width="80%" class="border-all" style="padding: 6px">
                <tr>
                    <td style="text-align:center;">{{ $job->created_at}}</td>
                </tr>
            </table>
        </td>
        <td></td>
        <td colspan="3">
            Job No:
            <table width="80%" class="border-all" style="padding: 6px">
                <tr>
                    <td style="text-align:center">{{$job->job_no}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="font-size: 13px">Your Details</td>
        <td colspan="6" style="text-align: left;vertical-align: bottom" align="">
            <hr>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Studio Name</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->f_name .' '.$job->customer->l_name}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Address</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->address}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Contact Person</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->marketeer->first_name .' '.$job->customer->marketeer->last_name}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Tel</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->mobile .' / '.$job->customer->telephone}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Email</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->email}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Agent</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->customer->marketeer->first_name.' '.$job->customer->marketeer->last_name}} / {{$job->customer->marketeer->mobile}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Job Creator</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->employee->first_name.' '.$job->employee->last_name}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Couple Name</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->couple_name}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    {{--<tr>
        <td></td>
        <td colspan="1">Due Date</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->due_date}}</td>
                </tr>
                --}}{{--<tr>
                    <td></td>
                </tr>--}}{{--
            </table>
        </td>
        <td colspan="2"></td>
    </tr>--}}
    <tr>
        <td></td>
        <td colspan="1">Delivery</td>
        <td colspan="2">
            <table width="100%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->delivery}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        {{--<td colspan="2">Delivery Date</td>--}}
        <td colspan="2">
            <table width="102%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->due_date}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="1">Album Type</td>
        <td colspan="6">
            <table width="80%" class="border-all" style="padding: 5px">
                <tr>
                    <td>{{$job->data[0]->master->name}}</td>
                </tr>
                {{--<tr>
                    <td></td>
                </tr>--}}
            </table>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 13px">Job Specification</td>
        <td colspan="5" style="text-align: left;vertical-align: bottom" align="">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            <table
                    id="orderTable" style="padding: 3px">
                <tbody>
                <tr>
                    <td>
                        Album No
                    </td>
                    <td class="border-all" align="center">1</td>
                    <td class="border-all" align="center">2</td>
                    <td class="border-all" align="center">3</td>
                    <td class="border-all" align="center">4</td>
                    <td class="border-all" align="center">5</td>
                </tr>
                <tr>
                    <td>
                        Album Size
                    </td>
                    <?php $len = 0; ?>
                    @foreach($job_album as $key => $album)
                        <td class="border-all">  {{$album->album_size}}</td>
                        <?php $len++; ?>
                    @endforeach
                    @for($i=$len; 5 > $i; $i++)
                        <td class="border-all">  -</td>
                    @endfor
                </tr>
                <tr>
                    <td>
                        Pages
                    </td>
                    @foreach($job_album as $key => $album)
                        <td class="border-all">  {{$album->pages}}</td>
                    @endforeach
                    @for($i=$len; 5 > $i; $i++)
                        <td class="border-all">  -</td>
                    @endfor
                </tr>

                <?php $index = 1;$pos = 0; ?>
                <tr>
                    <td>{{$job_data[$pos]->name}}</td>
                    @foreach($job->data as $key => $data)

                        <td class="border-all">
                            @if((($index-1) > 0 && ($index-1) < 5) && $job->data[($index+4)]->master->name == 'None')
                                -
                            @else
                                {{$data->master->name != 'None' ? $data->master->name : '-'}}
                            @endif
                        </td>
                        @if($index%5 == 0 && sizeof($job->data) != $index)
                </tr>
                <tr>
                    <?php $pos++; ?>
                    <td>{{$job_data[$pos]->name}}</td>
                    @endif
                    <?php $index++; ?>
                    @endforeach
                </tr>

                </tbody>
            </table>

        </td>
    </tr>

    <tr>
        <td colspan="7">Comments</td>
    </tr>
    <tr>
        <td colspan="7">
            <table width="100%" class="border-all">

                <tr>
                    <td><?php echo $job->remark;?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7">
            <table width="95%">
                <tr>
                    <td>......................</td>
                    <td>......................</td>
                    <td>.........................</td>
                    <td>......................</td>
                    <td>......................</td>
                    <td>......................</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            <table width="95%">
                <tr>
                    <td> Downloader</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp; Editor</td>
                    <td>Planing</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;Printer</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;Quality</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;Cashier</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br pagebreak="true"/>

    