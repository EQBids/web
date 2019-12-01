
@include('web.contractor.orders.order_details')

<style>
input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>
{!! csrf_field() !!}
<h4 class="mt-50">{{ __('Suppliers') }}</h4>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table id="suppliers-table" class="table table-bordered table-striped">
                <thead>
                    <th >{{ __('Name') }}</th>
                    <th >{{ __('City') }}</th>
                    <th >{{ __('Has bid') }}
                        @if($order->can_assign_bids)
                            <a class="btn btn-sm btn-info pull-right" href="{{ route('contractor.orders.bids',[$order->id]) }}">
                                <i class="fa fa-gavel fa-bold "></i>Accept bids
                            </a>
                        @endif
                    </th>
                    <th>{{ __('Bid Total') }}</th>
                    <th>{{ __('Contract') }}</th>
                    <th >{{ __('Import Signed Contract') }}</th>
                </thead>
                <tbody>
                    @foreach($order->suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->city?$supplier->city->name:'' }}</td>
                            <td class="{{ $supplier->pivot->bid?'table-success':'table-danger' }}">{{ $supplier->pivot->bid?__('YES'):__('NO') }}</td>
                            <td>{{ isset( $supplier->pivot->bid) ? '$' . $supplier->pivot->bid->amount : ''}}</td>
                            <td style="width:114px;">
                                @if( $supplier->pivot->order->status == '7' && isset( $supplier->pivot->bid) && $supplier->pivot->bid->contract != '')
                                <a target="_blank"  href="../../storage/suppliers/{{ isset( $supplier->pivot->bid) ?  $supplier->pivot->bid->contract : ''}}">
                                    <label class="custom-file-upload">
                                        <i class="fa fa-cloud-download"></i>Download
                                    </label>
                                </a>
                                
                                @endif
                            </td>
                            <td style="width:100px;">
                                @if( $supplier->pivot->order->status == '7' && isset( $supplier->pivot->bid) && $supplier->pivot->bid->contract != '')
                                <label for="file-upload" class="custom-file-upload">
                                    <i class="fa fa-cloud-upload"></i>Upload
                                </label>
                                <input id="file-upload" name="image" onchange="uploadFile({{ $supplier->pivot->bid->id }})" class="file_upload_{{ $supplier->pivot->bid->id }}" type="file"/>
                               
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="application/javascript">

        function uploadFile(id){
            var formData = new FormData();
            formData.append('file', $('.file_upload_'+ id)[0].files[0]);
            formData.append('bidId', id);
            $.ajax({
                url:'uploadContract',
                data: formData,
                type:'post',
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success:function(response){
                    alert("The contract has been submitted and an email has been sent to the supplier");
                }
            });
        }
        $('#suppliers-table').DataTable({
            "columnDefs": [
                { "width": "40%", "targets": 0 },
                { "width": "30%", "targets": 1 },
                { "width": "30%", "targets": 2 }
            ]
        });
    </script>
@endpush

