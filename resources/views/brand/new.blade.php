@extends("layout")
@section("title","Create a new brand")
@section("contentHeader","Create a new brand")
@section("content")
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Please enter</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" action="{{url("admin/save-brand")}}" method="post">
            @method("POST")
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Brand Name</label>
                    <input class="form-control @error("brand_name") is-invalid @enderror" type="text" name="brand_name" placeholder="Enter Name"/>
                    @error("brand_name")
                    <span class="error invalid-feedback">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

