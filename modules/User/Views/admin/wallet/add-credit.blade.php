@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('user.admin.wallet.store', ['id' => $row->id]) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-9">
                    <div class="d-flex justify-content-between mb20">
                        <div class="">
                            <h1 class="title-bar">{{ __('Add credit for :name', ['name' => $row->display_name]) }}</h1>
                        </div>
                    </div>
                    @include('admin.message')
                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('Add credit') }}</strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="wallet_id" value="{{ $row->id }}" class="form-control">
                                    <div class="form-group">
                                        <label>{{ __('Balance') }}</label>
                                        <input type="number" name="balance" readonly value="{{ $row->balance }}"
                                            step="0.1" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Credit Amount') }}</label>
                                        <input type="number" name="credit_amount" value="0" step="0.1" min=0
                                            class="form-control">
                                    </div>
                                    <!-- VD:17052023 - Add debit and reason label -->
                                    <div class="form-group">
                                        <label>{{ __('Debit Amount') }}</label>
                                        <input type="number" name="debit_amount" value="0" step="0.1"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Reason') }} *</label>
                                        <textarea name="reason_comment" value="0" step="0.1" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit">{{ __('Add now') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="panel mt-2">
            <div class="panel-title"><strong>{{ __('Latest Transactions') }}</strong></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Gateway') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Description') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($transactions))
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>
                                            @if ($transaction->payment->gateway_obj)
                                                {{ $transaction->payment->gateway_obj->getDisplayName() ?? '' }}
                                            @endif
                                        </td>
                                        <td><span
                                                class="badge badge-{{ $transaction->status_class }}">{{ $transaction->status_name ?? '' }}</span>
                                        </td>
                                        <td>
                                            @if (!empty($transaction->meta['admin_deposit']))
                                                {{ __('Deposit by :name', ['name' => $transaction->author->display_name ?? '']) }}
                                            @endif
                                        </td>
                                        <td>{{ display_datetime($transaction->created_at) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">{{ __('No data found') }}</td>
                                </tr>
                            @endif
                        </tbody>
                        {{ $transactions->links() }}
                    </table>
                </div>
            </div>
        </div>
    @endsection
