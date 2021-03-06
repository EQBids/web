@extends('web.supplier.layout')

@section('content')

    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-lg-12">
            <div class="alert alert-warning">
                <p>
                    {{ __('The order has been closed and you must confirm the bid. 
                        only the contractor can reopen the bids. ATTETION: You must add the unit price + market place fee to your countract. See the value below') }}

                    <div class="row">
                        <div class="col-lg-12">
                            @if($bid->is_accepted && $bid->status==\App\Models\Supplier\Bid::STATUS_ACTIVE)

                                <form action="{{ route('supplier.bids.close',[$bid->id]) }}" data-parsley-validate enctype="multipart/form-data" method="post">
                                    {{ csrf_field() }}

                                    @if($bid->is_accepted && $bid->status==\App\Models\Supplier\Bid::STATUS_ACTIVE)
                                    
                                    <label for="file-upload" class="custom-file-upload">
                                        <i class="fa fa-cloud-upload"></i>Attachment
                                    </label>
                                    <input id="file-upload" onChange="uploadFile();" name="image"  type="file" required/>
                               
                                    @endif
                                    <br>
                                    <input type="submit"  class="btn btn-info pull-right" value="{{ __('Confirm and close the bid') }}" />
                                </form>

                            @endif
                        </div>
                    </div>

                </p>
            </div>

        </div>
    </div>

    <script>
        function uploadFile(){
            alert('File has been uploaded');
        }
               
    </script>

    @include('web.supplier.bids.details')



@endsection
