<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

//$user = JFactory::getUser();
//$userId = $user->get('id');

$cKey = JRequest::getVar('ckey');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kelioniuimportas&view=import&ckey=' . $cKey); ?>" method="post" name="adminForm" id="adminForm">
    <div class="clr"> </div>
    <fieldset id="filter-bar">       
        <div class="filter-select fltlft"> 
            <select name="filter_rodyti_importuotas" class="inputbox" onchange="this.form.submit()">
                <?php echo JHtml::_('select.options', array('Visos', 'Neimportuotos'), 'value', 'text', $this->state->get('filter.rodyti_importuotas')); ?>
            </select>              
            <select name="filter_traveltype_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_OPTION_SELECT_TRAVLETYPE'); ?></option>
                <?php echo JHtml::_('select.options', $this->travelTypes, 'value', 'text', $this->state->get('filter.traveltype_id')); ?>
            </select>   
            <select name="filter_country_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_KELIONIUIMPORTAS_IMPORT_OPTION_SELECT_COUNTRY'); ?></option>
                <?php echo JHtml::_('select.options', $this->countries, 'value', 'text', $this->state->get('filter.country_id')); ?>
            </select>                                  
        </div>
        <div class="filter-select fltrt">  
            <select name="import_category_id" class="inputbox">
                <option value=""><?php echo '- Pasirinkite kategoriją importui -'; ?></option>
                <?php echo JHtml::_('select.options', $this->importCategories, 'value', 'text'); ?>
            </select>                                  
        </div>        
    </fieldset>
    <div class="clr"> </div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
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
                    <?php echo JText::_('COM_KELIONIUIMPORTAS_HEADING_ID'); ?>                                                               					
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            //echo serialize($this->items);
            $traveltype_id = $this->state->get('filter.traveltype_id');
            $country_id = $this->state->get('filter.country_id');
            $rodytiVisas = $this->state->get('filter.rodyti_importuotas') == 0;
            $i = 0;
            if ($traveltype_id) {
                foreach ($this->items as $item) :
                    $jauImport = $item->isImported();
                    if ((!$country_id || ($country_id > 0 && $item->salys->contains($country_id))) && ($rodytiVisas || (!$rodytiVisas && !$jauImport))) {
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center">
                                <?php
                                if (!$jauImport) {
                                    echo JHtml::_('grid.id', $i, $item->id);
                                } else {
                                    echo '<img src="' . JURI::base() . 'components/com_kelioniuimportas/assets/images/icon-16-check.png">';
                                }
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
                                <?php echo $this->escape($item->id); ?></span>                                                                                         
                            </td>
                        </tr>
                        <?php
                        $i++;
                    } endforeach;
            } else {
                echo '<tr><td class="center" colspan="5"><h3>Pasirinkite kelionės tipą</h3></td></tr>';
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
        <input type="hidden" name="travelTypeId" value="<?php echo $traveltype_id; ?>" />        
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>