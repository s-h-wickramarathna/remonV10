<style type="text/css">

    td {
        font-weight: 400;
        font-size: 8px;
    }

    .details, .details > th, .details > td {
        border-collapse: collapse;
        padding: 5px;
    }

    th {
        text-align: left;
    }

    .border {
        border-width: 1px;
        border-style: solid;
    }

    .border-left {
        border-left-style: solid;
        border-width: 1px;
    }

    .border-right {
        border-right-style: solid;
        border-width: 1px;
    }

    .border-top {
        border-top-style: solid;
        border-width: 1px;
    }

    .border-bottom {
        border-bottom-style: solid;
        border-width: 1px;
    }

</style>
<table width="100%" style="padding-bottom: 10px">
    <tbody>
    <tr><!--row1-->
        <td colspan="4" style="vertical-align: top">

        </td>

        <td colspan="3" style="text-align:right">
            <b><span style="font-weight: 900;font-size: 12px">Ramon Album</b></span><br>
            <span style="font-weight: 800;font-size: 9px"><b>Maharagama</b></span><br>
            <span style="font-weight: 800;font-size: 9px"><b>Tel : 011 2089448 / 011 3098756</b></span><br>
        <!--<span style="font-weight: 800;font-size: 9px"><b>chandimal@gmail.com</b></span><br>-->
            <span style="font-weight: 800;font-size: 9px"><b>www.ramonalbum.com</b></span><br>
        </td>

    </tr>

    <tr><!--row1-->
        <td colspan="7">
            <table>
                <tr>
                    <td width="50%">
                        <!--CUSTOMER SECTION-->
                        <table>
                            <tr>
                                <td colspan="3" style="font-weight: bold;font-size: 12px;">
                                    Marketeers Customer List
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    Marketeer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    <b>{{$page_header['marketeer']}}</b>
                                </td>
                            </tr>
                        </table>


                    </td>

                    <td width="15%">
                        <!--SPACE-->
                    </td>

                    <td width="30%">
                        <!--DETAILS-->
                        <table>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    Report Date
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$page_header['date']}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table width="100%">
    <tbody>
    <tr>
        <td width="100%">
            <table class="details" width="100%"  style="border-bottom-style:solid">
                <thead>
                <tr  class="border">
                    <th class="border-top border-bottom"  width="6%">#</th>
                    <th class="text-center border-top border-bottom" width="22%" style="font-weight:normal;">Name</th>
                    <th class="text-center border-top border-bottom" width="33%" style="font-weight:normal;">Address / E-mail</th>
                    <th class="text-center border-top border-bottom" width="12%" style="font-weight:normal;">Mobile</th>
                    {{--  <th rowspan="2" class="text-center" style="font-weight:normal;">Telephone</th>--}}
                    <th class="text-center border-top border-bottom" width="15%" style="font-weight:normal;">Marketeer</th>
                    <th class="text-center border-top border-bottom" width="12%" style="font-weight:normal;">Credit Limit / Period</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $customer)
                    <tr>
                        <td style="font-weight: normal;">
                            {{$key + 1}}
                        </td>
                        <td style="text-align: left;">{{$customer->f_name .' '.$customer->l_name}}</td>
                        <td style="text-align: left;"><?php echo $customer->address .'<br>'.$customer->email ?></td>
                        <td >{{$customer->mobile}} </td>
                        <td>{{$customer->marketeer_name}}</td>
                        <td style="text-align: right;" ><?php echo $customer->credit_limit .'<br>'. $customer->credit_period ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
    