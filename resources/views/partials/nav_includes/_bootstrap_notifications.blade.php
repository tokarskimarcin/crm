<script>
    $(document).ready(function() {
        $.notify({
            title: 'Bootstrap notify',
            message: 'Turning standard Bootstrap alerts into "notify" like notifications',
            url: 'https://github.com/mouse0270/bootstrap-notify',
            target: '_blank'
        },{
            // settings
            type: 'warning',
            delay: null,
            animate: {
                enter: 'animated lightSpeedIn',
                exit: 'animated lightSpeedOut'
            },
            placement: {
                from: "top",
                align: "center"
            },
            offset: 50
        });
    
    });
</script>