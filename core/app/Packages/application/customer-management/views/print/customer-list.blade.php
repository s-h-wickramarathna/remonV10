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
        border-bottom-style: dotted;
        border-width: 1px;
    }

</style>
<table width="100%" style="padding-bottom: 10px">
    <tbody>
    <tr><!--row1-->
        <td colspan="4" style="vertical-align: top">

        </td>

        <td colspan="3" style="text-align:right">
            <span style="font-weight: 900;font-size: 12px"><b>Ramon Album</b></span><br>
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
                                    Customer Added History
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    Customer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    <b>{{$page_header['customer']}}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="25%">
                                    Telephone
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    {{$page_header['cus_all']}}
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    Marketeer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    {{$page_header['marketeer']}}
                                </td>
                            </tr>
                        </table>


                    </td>

                    <td width="15%">
                        <!--SPACE-->
                    </td>

                    <td width="50%">
                        <!--DETAILS-->
                        <table>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    From
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    <b>{{$page_header['from']}}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="40%">
                                    To
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    <b>{{$page_header['to']}}</b>
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
            <table class="details" width="100%" style="border-bottom-style:solid">
                <tr>
                    <td align="center" width="5%" class="border-top">
                        #
                    </td>
                    <td width="30%" class="border-top">Customer</td>
                    <td width="15%" class="border-top">Contact</td>
                    <td width="20%" class="border-top">email</td>
                    <td width="15%" class="border-top">Marketeer</td>
                    <td width="15%" class="border-top">Added Date</td>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;?>
                @foreach($orders as $order)
                    <tr id="{{$i}}">
                        <td align="center" class="border-top">{{$i}}</td>
                        <td class="border-top">{{$order->f_name.' '.$order->l_name}}<br>{{$order->address}}</td>
                        <td class="border-top">{{$order->mobile}}<br>{{$order->telephone}}</td>
                        <td class="border-top">{{$order->email}}</td>
                        <td class="border-top">{{$order->marketeer->first_name.' '.$order->marketeer->last_name}}</td>
                        <td class="border-top">{{$order->created_at}}</td>

                    </tr>
                    <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
    