<script>
$("#change_department").on('change', function() {
    var department_info_id = $("#change_department").val();
    $.ajax({
        type: "POST",
        url: '{{ route('api.changeDepartment') }}',
        data: {
          "department_info_id":department_info_id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            location.reload();
        }
    });
});
</script>
