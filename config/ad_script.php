<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#provinces').change(function() {
        var name_th = $(this).val();
        console.log(name_th);
        $.ajax({
            type: "POST",
            url: 'config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'provinces'
            },
            success: function(data) {
                console.log(data);
                 $('#amphures').html(data);
                 $('#districts').html(' ');
                 $('#zip_code').val(' ');

            }
        });
    });

    $('#amphures').change(function() {
        var name_th = $(this).val();
        console.log(name_th);
        $.ajax({
            type: "POST",
            url: 'config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'amphures'
            },
            success: function(data) {
              // console.log(data);
               $('#districts').html(data);
               $('#zip_code').val(' ');
            }
        });

    });

    $('#districts').change(function() {
        var name_th = $(this).val();
       // console.log(name_th);
        $.ajax({
            type: "POST",
            url: 'config/ad_db.php',
            data: {
                name_th: name_th,
                function: 'districts'
            },
            success: function(data) {
                console.log(data);
               $('#zip_code').val(data);

            }
        });

    });
</script>