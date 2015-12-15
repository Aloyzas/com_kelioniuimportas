<?php
// no direct access
defined('_JEXEC') or die;

//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.multiselect');
//$user = JFactory::getUser();
//$userId = $user->get('id');

$cKey = JRequest::getVar('ckey');
?>

<script src="<?php echo JURI::base() ?>components/com_kelioniuimportas/assets/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
    var downloadList = ["<?php echo implode('","', $this->downloadList); ?>"];
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
            download(downloadList[ai-1]); 
        } else {
            window.location = "<?php echo JRoute::_('index.php?option=com_kelioniuimportas&view=import&ckey=' . $cKey, false); ?>";
        }                               
    }
    
    function download( id ) {
        $.ajax({
            url: '<?php echo JURI::base() ?>components/com_kelioniuimportas/helpers/ajax-requests.php',
            type: 'GET',
            dataType: 'html',
            data: { "action": "downloadXML", "id": id, "ckey": "<?php echo $cKey; ?>" },
            success: function() {
                next();
            }
        });        
    }
</script>

<div align="center">
    <h2>Atsiunčiami duomenys. Prašome palaukti.</h2>

    <h1 id="progresas">0 %</h1>
</div>

<script type="text/javascript">
    $(function() {
        next();
    });    
</script>