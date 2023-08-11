@extends('user_dashboard.layouts.app')
@section('css')
<!--daterangepicker-->
<link rel="stylesheet" type="text/css" href="{{theme_asset('public/css/daterangepicker.css')}}">
@endsection

@section('content')
<section class="min-vh-100">
    <div class="my-30">
        <div class="container-fluid">
            <!-- Page title start -->
            <div>
                <h3 class="page-title">{{ __('Transactions')}} </h3>
            </div>
            <!-- Page title end-->

            <!--Filter section start-->
            <div class="row  mt-4">
                <div class="col-xl-12">
                    <form action="" method="get">
                        <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                        <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
                        <div class="d-flex justify-content-between bg-secondary rounded px-4 py-3 shadow ">
                            <div class="d-flex flex-wrap">
                                <div class="pr-3 mt-2">
                                    <div class="daterange_btn" id="daterange-btn">
                                        <span id="drp"><i class="fa fa-calendar"></i>{{ __('Pick a date range')}}</span>
                                    </div>
                                </div>
                                @php
                                    $transactionTypes = getTransactionTypes();
                                @endphp
                                <div class="pr-3 mt-2">
                                    <select class="form-control w-200p" id="type" name="type">
                                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>{{ __('All')  }}</option>
                                        @foreach ($transactionTypes as $transactionTypeKey => $transactionTypeName)
                                            <option value="{{ $transactionTypeKey }}" {{ $type == $transactionTypeKey ? 'selected' : '' }}>
                                                {{ __(str_replace('_', ' ', $transactionTypeName)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="pr-3 mt-2">
                                    <select class="form-control w-200p" id="status" name="status">
                                        <option value="all" <?= ($status == 'all') ? 'selected' : '' ?>>{{ __('All Status') }}
                                        </option>
                                        <option value="Success" <?= ($status == 'Success') ? 'selected' : '' ?>>
                                           {{ __('Success') }}
                                        </option>
                                        <option value="Pending" <?= ($status == 'Pending') ? 'selected' : '' ?>>
                                            {{ __('Pending') }}
                                        </option>
                                        <option value="Refund" <?= ($status == 'Refund') ? 'selected' : '' ?>>
                                           {{ __('Refund') }}
                                        </option>
                                        <option value="Blocked" <?= ($status == 'Blocked') ? 'selected' : '' ?>>
                                            {{ __('Blocked') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="pr-3 mt-2">
                                    <select class="form-control w-200p" id="wallet" name="wallet">
                                        <option value="all" <?= ($wallet == 'all') ? 'selected' : '' ?>>{{ __('All Currency')}}
                                        </option>
                                        @foreach($wallets as $res)
                                            <option value="{{ optional($res->currency)->id }}" <?= ($res->currency_id == $wallet) ? 'selected' : '' ?>>{{ optional($res->currency)->code }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary px-4 py-2">{{ __('Filter')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--Filter end-->

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bg-secondary mt-3 shadow">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="pl-5" scope="col">{{ __('Date')}}</th>
                                            <th scope="col">{{ __('Description')}}</th>
                                            <th scope="col">{{ __('Status')}}</th>
                                            <th scope="col">{{ __('Fee')}}</th>
                                            <th class="text-right pr-5" scope="col">{{ __('Amount')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if($transactions->count()>0)
                                                @foreach($transactions as $key=>$transaction)
                                                    <tr click="0" data-toggle="modal" data-target="#collapseRow{{$key}}" aria-expanded="false" aria-controls="collapseRow{{$key}}" class="show_area cursor-pointer" trans-id="{{$transaction->id}}" id="{{$key}}">
                                                        <td class="pl-5">
                                                            <p class="font-weight-600 text-16 mb-0">{{ $transaction->created_at->format('jS F') }}</p>
                                                            <p class="td-text">{{ $transaction->created_at->format('Y') }}</p>
                                                        </td>

                                                        <!-- Transaction Type -->
                                                        @if(empty($transaction->merchant_id))
                                                            @if(!empty($transaction->end_user_id))
                                                                <td class="text-left">
                                                                    @if($transaction->transaction_type_id)
                                                                        @if($transaction->transaction_type_id==Request_From)
                                                                            <p class="text-16 mb-0">
                                                                                {{ getColumnValue($transaction->end_user)  }}
                                                                            </p>
                                                                            <p class="td-text">{{ __('Request Sent')}}</p>
                                                                        @elseif($transaction->transaction_type_id==Request_To)
                                                                            <p class="text-16 mb-0">
                                                                                {{ getColumnValue($transaction->end_user) }}
                                                                            </p>
                                                                            <p class="td-text">{{ __('Request Received')}}</p>

                                                                        @elseif($transaction->transaction_type_id == Transferred)
                                                                            <p class="text-16 mb-0">
                                                                                {{ getColumnValue($transaction->end_user) }}
                                                                            </p>

                                                                            <p class="td-text">{{ __('Transferred') }}</p>

                                                                        @elseif($transaction->transaction_type_id == Received)
                                                                            <p class="text-16 mb-0">
                                                                                {{ getColumnValue($transaction->end_user) }}
                                                                            </p>
                                                                            <p class="td-text">{{ __('Received')}}</p>
                                                                        @else
                                                                            <p>{{ __(str_replace('_',' ',optional($transaction->transaction_type)->name)) }}</p>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            @else
                                                                <?php
                                                                    if (isset(optional($transaction->payment_method)->name))
                                                                    {
                                                                        if (optional($transaction->payment_method)->name == 'Mts')
                                                                        {
                                                                            $payment_method = settings('name');
                                                                        }
                                                                        else
                                                                        {
                                                                            $payment_method = optional($transaction->payment_method)->name;
                                                                        }
                                                                    }
                                                                ?>
                                                                <td class="text-left">
                                                                    <p class="text-16 mb-0">
                                                                        @if(optional($transaction->transaction_type)->name == 'Deposit')
                                                                            @if (optional($transaction->payment_method)->name == 'Bank')
                                                                            {{ optional($transaction->transaction_type)->name . ' ' . 'via' . ' ' . $payment_method . ' ' .  optional($transaction->bank)->bank_name }}
                                                                            @else
                                                                                @if(!empty($payment_method))
                                                                                {{ optional($transaction->transaction_type)->name . ' ' . 'via' . ' ' . $payment_method }}
                                                                                @endif
                                                                            @endif

                                                                        @elseif(optional($transaction->transaction_type)->name == 'Exchange_To' || optional($transaction->transaction_type)->name == 'Exchange_From')
                                                                            {{ __(str_replace('_',' ',optional($transaction->transaction_type)->name)) .' ' . optional($transaction->currency)->code }}

                                                                        @elseif(optional($transaction->transaction_type)->name == 'Withdrawal')
                                                                            @if(!empty($payment_method))
                                                                            {{ __('Payout via') }} {{ $payment_method }}
                                                                            @endif

                                                                        @elseif(optional($transaction->transaction_type)->name == 'Transferred' && $transaction->user_type = 'unregistered')
                                                                            {{ ($transaction->email) ? $transaction->email : $transaction->phone }} <!--for send money by phone - mobile app-->
                                                                        @elseif(optional($transaction->transaction_type)->name == 'Request_From' && $transaction->user_type = 'unregistered')
                                                                            {{ ($transaction->email) ? $transaction->email : $transaction->phone }} <!--for send money by phone - mobile app-->
                                                                        @endif
                                                                    </p>

                                                                    @if($transaction->transaction_type_id)
                                                                        <p class="td-text">
                                                                            @if($transaction->transaction_type_id==Request_From)
                                                                                {{ __('Request Sent')}}
                                                                            @elseif($transaction->transaction_type_id==Request_To)
                                                                                {{ __('Request Received')}}

                                                                            @elseif($transaction->transaction_type_id == Withdrawal)
                                                                               {{ __('Payout')}}
                                                                            @else
                                                                                <p>{{ __(str_replace('_',' ',optional($transaction->transaction_type)->name)) }}</p>
                                                                            @endif
                                                                        </p>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @else
                                                            <td>
                                                                <p class="text-16 mb-0">{{ optional($transaction->merchant)->business_name }}</p>
                                                                @if($transaction->transaction_type_id)
                                                                    <p>{{ __(str_replace('_',' ',optional($transaction->transaction_type)->name)) }}</p>
                                                                @endif
                                                            </td>
                                                        @endif

                                                        <!--Status -->
                                                        <td>
                                                            <span id="status_{{$transaction->id}}" class="badge {{ $transaction->status }}">
                                                                {{
                                                                    (
                                                                        ($transaction->status == 'Blocked') ? __("Cancelled") :
                                                                        (
                                                                            ($transaction->status == 'Refund') ? __("Refunded") : __($transaction->status)
                                                                        )
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>

                                                        <!-- Fees -->
                                                        <td>
                                                            <p>{{ ($transaction->charge_percentage == 0) && ($transaction->charge_fixed == 0) ? '-' : formatNumber(abs($transaction->total)-abs($transaction->subtotal), optional($transaction->currency)->id) }}</p>
                                                        </td>


                                                        <!-- Amount -->
                                                        @if($transaction->transaction_type_id == Deposit)
                                                            @if($transaction->subtotal > 0)
                                                                <td class="text-right pr-5">
                                                                    <p><span class="text-16 font-weight-600"> +{{ formatNumber($transaction->subtotal, optional($transaction->currency)->id) }}</span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @endif
                                                        @elseif($transaction->transaction_type_id == Withdrawal)
                                                            <td class="text-right pr-5">
                                                                <p><span class="text-16 font-weight-600"> -{{ formatNumber($transaction->subtotal, optional($transaction->currency)->id) }}</span> <span class="c-code"> ({{ optional($transaction->currency)->code }}) </span></p>
                                                            </td>
                                                        @elseif($transaction->transaction_type_id == Payment_Received)
                                                            @if($transaction->subtotal > 0)
                                                                @if($transaction->status == 'Refund')
                                                                    <td class="text-right pr-5">
                                                                        <p><span class="text-16 font-weight-600">-{{ formatNumber($transaction->subtotal, optional($transaction->currency)->id) }}</span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                        <p>{{ optional($transaction->currency)->code }}</p>
                                                                    </td>
                                                                @else
                                                                    <td class="text-right pr-5">
                                                                        <p><span class="text-16 font-weight-600">+{{ formatNumber($transaction->subtotal, optional($transaction->currency)->id) }}</span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                    </td>
                                                                @endif
                                                            @elseif($transaction->subtotal == 0)
                                                                <td>
                                                                    <p><span class="text-16 font-weight-600"> {{ formatNumber($transaction->subtotal, optional($transaction->currency)->id) }} </span><span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @elseif($transaction->subtotal < 0)
                                                                <td class="text-right pr-5">
                                                                    <p><span class="text-16 font-weight-600"> {{ formatNumber($transaction->subtotal, optional($transaction->currency)->type) }} </span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @endif
                                                        @else
                                                            @if($transaction->total > 0)
                                                                <td class="text-right pr-5">
                                                                    <p> <span class="text-16 font-weight-600"> {{ "+".formatNumber($transaction->total, optional($transaction->currency)->id) }} </span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @elseif($transaction->total == 0)
                                                                <td class="text-right pr-5">
                                                                    <p><span class="text-16 font-weight-600"> {{ formatNumber($transaction->total, optional($transaction->currency)->id) }} </span><span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @elseif($transaction->total < 0)
                                                                <td class="text-right pr-5">
                                                                    <p><span class="text-16 font-weight-600">{{ formatNumber($transaction->total + formatNumber(abs($transaction->total)-abs($transaction->subtotal)), optional($transaction->currency)->id) }} </span> <span class="c-code">({{ optional($transaction->currency)->code }})</span></p>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    </tr>

                                                        <!-- Modal -->
                                                        <div class="modal fade-scale" id="collapseRow{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body p-0">
                                                                        <button type="button" class="close text-28  pr-4 mt-2" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>

                                                                        <div class="row activity-details" id="loader_{{$transaction->id}}"
                                                                            style="min-height: 400px">
                                                                            <div class="col-md-5 bg-primary">
                                                                                    <div id="total_{{$key}}" class="p-center mt-5">

                                                                                    </div>
                                                                            </div>
                                                                        <div class="col-md-7 col-sm-12 text-left p-0">
                                                                                <div class="preloader transaction-loader" style="display: none;">
                                                                                    <div class="loader"></div>
                                                                                </div>

                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title" id="exampleModalLabel">{{ __('Transaction details') }}</h3>
                                                                                </div>

                                                                                <div id="html_{{$key}}" class="px-4 mt-4">

                                                                                </div>
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="text-center mb-4">
                                                                                        @if( $transaction->transaction_type_id == Payment_Sent && $transaction->status == 'Success' && !isset($transaction->dispute->id))
                                                                                            <a id="dispute_{{$transaction->id}}" href="{{url('/dispute/add/').'/'.$transaction->id}}" class="btn btn-grad btn-sm">{{ __('Open Dispute')}}</a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center p-4">
                                                            <img src="{{ theme_asset('public/images/banner/notfound.svg') }}" alt="notfound">
                                                             <p class="mt-4">{{ __('Sorry, Transaction not found.') }} </p>
                                                        </td>
                                                    </tr>
                                                @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<!--daterangepicker-->
<script src="{{theme_asset('public/js/daterangepicker.js')}}" type="text/javascript"></script>
@include('user_dashboard.layouts.common.check-user-status')

<script>
    $(window).on('load', function()
    {
        var sDate;
        var eDate;
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),

            },
            function (start, end) {
                sDate = moment(start, 'MMMM D, YYYY').format('DD-MM-YYYY');
                $('#startfrom').val(sDate);
                eDate = moment(end, 'MMMM D, YYYY').format('DD-MM-YYYY');
                $('#endto').val(eDate);
                $('#daterange-btn span').html(sDate + ' - ' + eDate);
            }
        )

        var startDate = "{!! $from !!}";
        var endDate = "{!! $to !!}";
        if (startDate == '') {
            $('#daterange-btn span').html('<i class="fa fa-calendar"></i> {{ __('message.dashboard.transaction.date-range') }}');
        } else {
            $('#daterange-btn span').html(startDate + ' - ' + endDate);
        }
    });
</script>

@include('common.user-transactions-scripts')
@endsection
