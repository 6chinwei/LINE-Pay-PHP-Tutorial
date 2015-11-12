 $(function() {
    var backendUrl = './LinePayBackend.php';
    var $responseDiv = $('#response');
    $responseDiv.closest('.panel').hide();

    $('#refundForm, #recordForm').submit(function(event) {
        event.preventDefault();
        var params = {};
        $(this).serializeArray().map(function(x){params[x.name] = x.value;}); 
        
        $responseDiv.closest('.panel').show().end().html('Loading...');

        $.ajax({
            method: 'POST',
            dataType: 'text',
            url: backendUrl,
            data: params
        }).always(function(data) {
            $responseDiv.html(data);
        });
    });
});
