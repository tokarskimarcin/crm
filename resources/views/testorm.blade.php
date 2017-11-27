@extends('layouts.main')

@section('content')

<button class="mybtn btn btn-danger">Click</button>


@endsection

@section('script')
<script>

$(".mybtn").on('click', function(){
    var conf = confirm("sdf");

    if (conf == true) {
      alert("tak");
    } else {
      alert("nie");
    }
});

</script>
@endsection
