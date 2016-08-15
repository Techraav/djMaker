$('#submit').click(function() {
    bootbox.confirm("Are you sure?", function(result) {
        if (result) {
            $(this).parents('form').submit();
        }
    });
});
