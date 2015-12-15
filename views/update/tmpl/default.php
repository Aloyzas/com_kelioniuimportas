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
    function checkAll(id_preffix, checked) {
        $('[id^="' + id_preffix + '"]:checkbox').attr('checked', checked);
        Joomla.isChecked(true);
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_kelioniuimportas&view=update&ckey=' . $cKey); ?>" method="post" name="adminForm" id="adminForm">
    <div class="clr"> </div>
    <div><h2>A. Kelionės esančios aktyvios mūsų DB, tačiau nebeegzistuojančios tiekėjo DB.</h2><p>Atnaujinant šias keliones jos bus pažymėtos neaktyviomis mūsų DB.</p><div>
            <div class="clr"> </div>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll('ab', this.checked)" />
                        </th>
                        <th width="10%">
                            <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_TITLE'); ?>
                        </th>
                        <th width="5%">
                            <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_COUNTRIES'); ?>                                                      
                        </th>                
                        <th>                    
                            <?php echo 'Aprašymas'; ?>                                                          					
                        </th>                
                        <th width="2%" class="nowrap">
                            <?php echo 'ZOO ID'; ?>                                                               					
                        </th>
                        <th width="2%" class="nowrap">
                            <?php echo JText::_('COM_KELIONIUIMPORTAS_HEADING_ID'); ?>                                                               					
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->itemsPasalintos as $item) :
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center">
                                <?php
                                echo '<input type="checkbox" id="ab' . $i . '" name="aid[]" value="' . $item->zoo_id . '" onclick="Joomla.isChecked(true);" />';
                                ?>
                            </td>
                            <td>
                                <?php echo $this->escape($item->pavadinimas); ?>                                            
                            </td>
                            <td class="center">
                                <?php echo $this->escape($item->salys->toString()); ?> 
                            </td>    
                            <td class="center">                       
                                <?php
                                echo $item->trumpas_aprasymas;
                                ?>
                            </td>                              
                            <td class="center">
                                <?php echo $this->escape($item->zoo_id); ?></span>                                                                                         
                            </td>                  
                            <td class="center">
                                <?php echo $this->escape($item->id); ?></span>                                                                                         
                            </td>
                        </tr>
                        <?php
                        $i++;

                    endforeach;
                    if ($i === 0) {
                        echo '<tr><td class="center" colspan="6"><h3>Įrašų nėra</h3></td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="15">
                            Viso kelionių: <?php echo $i; ?>
                        </td>
                    </tr>
                </tfoot>        
            </table> 
            <div class="clr"> </div>
            <div><h2>B. Kelionės esančios neaktyvios mūsų DB, tačiau aktyvios tiekėjo DB.</h2><p>Atnaujinant šias keliones jos bus pažymėtos aktyviomis mūsų DB.</p><div>
                    <div class="clr"> </div>
                    <table class="adminlist">
                        <thead>
                            <tr>
                                <th width="1%">
                                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll('bb', this.checked)" />
                                </th>
                                <th width="10%">
                                    <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_TITLE'); ?>
                                </th>
                                <th width="5%">
                                    <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_COUNTRIES'); ?>                                                      
                                </th>                
                                <th>                    
                                    <?php echo 'Aprašymas'; ?>                                                          					
                                </th>                
                                <th width="2%" class="nowrap">
                                    <?php echo 'ZOO ID'; ?>                                                               					
                                </th>
                                <th width="2%" class="nowrap">
                                    <?php echo JText::_('COM_KELIONIUIMPORTAS_HEADING_ID'); ?>                                                               					
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($this->itemsNeaktyvintos as $item) :
                                ?>
                                <tr class="row<?php echo $i % 2; ?>">
                                    <td class="center">
                                        <?php
                                        echo '<input type="checkbox" id="bb' . $i . '" name="bid[]" value="' . $item->zoo_id . '" onclick="Joomla.isChecked(true);" />';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $this->escape($item->pavadinimas); ?>                                            
                                    </td>
                                    <td class="center">
                                        <?php echo $this->escape($item->salys->toString()); ?> 
                                    </td>    
                                    <td class="center">                       
                                        <?php
                                        echo $item->trumpas_aprasymas;
                                        ?>
                                    </td>                              
                                    <td class="center">
                                        <?php echo $this->escape($item->zoo_id); ?></span>                                                                                         
                                    </td>                  
                                    <td class="center">
                                        <?php echo $this->escape($item->id); ?></span>                                                                                         
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                            if ($i === 0) {
                                echo '<tr><td class="center" colspan="6"><h3>Įrašų nėra</h3></td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="15">
                                    Viso kelionių: <?php echo $i; ?>
                                </td>
                            </tr>
                        </tfoot>        
                    </table>        
                    <div class="clr"> </div>
                    <div><h2>C. Kelionės kurių išvykimo informacija nesutampa su tiekėjo DB esančia informacija.</h2><p>Atnaujinant šitas keliones bus atnaujinta išvykimo datų informacija.</p><div>
                            <div class="clr"> </div>
                            <table class="adminlist">
                                <thead>
                                    <tr>
                                        <th width="1%">
                                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll('cb', this.checked)" />
                                        </th>
                                        <th width="10%">
                                            <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_TITLE'); ?>
                                        </th>
                                        <th width="5%">
                                            <?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_HEADING_COUNTRIES'); ?>                                                      
                                        </th>                
                                        <th>                    
                                            <?php echo 'Aprašymas'; ?>                                                          					
                                        </th> 
                                        <th width="2%" class="nowrap">
                                            <?php echo 'ZOO ID'; ?>                                                               					
                                        </th>                                        
                                        <th width="2%" class="nowrap">
                                            <?php echo JText::_('COM_KELIONIUIMPORTAS_HEADING_ID'); ?>                                                               					
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($this->itemsKeitimui as $item) :
                                        $kelXML = $this->items->getByID($item->id);
                                        $pasikeitusi = $kelXML && !$item->datos->compareTo($kelXML->datos);
                                        if ($pasikeitusi) {
                                            ?>
                                            <tr class="row<?php echo $i % 2; ?>">
                                                <td class="center">
                                                    <?php
                                                    echo '<input type="checkbox" id="cb' . $i . '" name="cid[]" value="' . $item->zoo_id . '" onclick="Joomla.isChecked(true);" />';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->escape($item->pavadinimas); ?>
                                                </td>
                                                <td class="center">
                                                    <?php echo $this->escape($item->salys->toString()); ?>
                                                </td>
                                                <td class="center">
                                                    <?php
                                                    echo $item->trumpas_aprasymas;
                                                    ?>
                                                </td>
                                                <td class="center">
                                                    <?php echo $this->escape($item->zoo_id); ?></span>
                                                </td>
                                                <td class="center">
                                                    <?php echo $this->escape($item->id); ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        } 
                                    endforeach;
                                    if ($i === 0) {
                                        echo '<tr><td class="center" colspan="6"><h3>Įrašų nėra</h3></td></tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="15">
                                            Viso kelionių: <?php echo $i; ?>
                                        </td>
                                    </tr>
                                </tfoot>        
                            </table>
                            <div>
                                <input type="hidden" name="task" value="" />
                                <input type="hidden" name="boxchecked" value="0" />     
                                <?php echo JHtml::_('form.token'); ?>
                            </div>
                            </form>