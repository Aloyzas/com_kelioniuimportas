<?php
// no direct access
defined('_JEXEC') or die;
?>

<script src="<?php echo JURI::base() ?>components/com_kelioniuimportas/assets/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
    var downloadList = <?php echo json_encode($this->items); ?>;
    var ai = 0;
    var started = false;
    
    function updateProgress ( ) {
        var proc = 0;
                       
        if (downloadList.length > 0) {
            proc = Math.round(100 / downloadList.length * ai);
        }
        
        $('#progresas').html(proc + ' %');       
    }
    
    function next ( ) {
        updateProgress(); 
        ai++;
        if (ai <= downloadList.length) {
            download(downloadList[ai-1].travelId, downloadList[ai-1].image, downloadList[ai-1].subdir, downloadList[ai-1].url); 
        } else {            
            $('#progresas').html('Baigta!');
        }                               
    }
    
    function printError(msg) {
        $('#logas').append('<p class="error">' + msg + '</p>');
    }
    
    function download( travelId, image, subdir, url ) {
        $.ajax({
            url: '<?php echo JURI::base() ?>components/com_kelioniuimportas/helpers/ajax-requests.php',
            type: 'GET',
            dataType: 'json',
            data: { "action": "downloadImage", "travelId": travelId, "image": image, "subdir": subdir, "url": url },
            complete: function(data) {
                if (data.responseText != '') { 
                    printError('klaida atsiun\u010diant paveiksl\u0117l\u012f ' + image + ' (' + data.responseText + ')');
                }
                next();
            },
            error: function() {
                printError('klaida atsiun\u010diant paveiksl\u0117l\u012f ' + image);
            }            
        });        
    }
</script>

<div align="center">
    <h2>Atsiunčiami paveiksleliai. Prašome palaukti.</h2>

    <h1 id="progresas">0 %</h1>
</div>
<div id="logas" align="center">

</div>

<script type="text/javascript">
    $(function() {
        next();
    });    
</script>