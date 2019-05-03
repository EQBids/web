 @extends('web.admin.layout')

 @section('content')

     <h2>{{ __('New industry') }}</h2>

     <form action="{{ route('admin.industries.store') }}" method="post" data-parsley-validate>
            @include('web.admin.industries.form')

         <div class="row">
             <div class="col-lg-12">
                 <a href="{{route('admin.industries.index')}}" class="btn btn-warning ">{{__("Back")}}</a>
                 <button type="submit" class="btn btn-primary">{{__("Create")}}</button>
             </div>
         </div>
     </form>

 @endsection