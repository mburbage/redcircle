<?php /* Smarty version 3.1.27, created on 2020-06-17 18:46:29
         compiled from "C:\Users\Michael1\Documents\GitHub\redcircle\app\public\wp-content\plugins\whmpress\templates\whmpress_bundle_pricing_table\default.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:14249934705eea65056fdf97_69550570%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6bb8fd0678d41055288a387cfd452855f4e1b47' => 
    array (
      0 => 'C:\\Users\\Michael1\\Documents\\GitHub\\redcircle\\app\\public\\wp-content\\plugins\\whmpress\\templates\\whmpress_bundle_pricing_table\\default.tpl',
      1 => 1591277505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14249934705eea65056fdf97_69550570',
  'variables' => 
  array (
    'name' => 0,
    'itemdata' => 0,
    'item' => 0,
    'tld' => 0,
    'order_link' => 0,
    'button_text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5eea650576dbe7_81257118',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5eea650576dbe7_81257118')) {
function content_5eea650576dbe7_81257118 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '14249934705eea65056fdf97_69550570';
?>
<div class="whmpress whmpress_pricing_bundle whmpress-01">
    <div class="pricing_bundle_heading">
        <div class="holder">
            <span><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</span>
        </div>
    </div>
    <div class="pricing_bundle_items">
        <?php
$_from = $_smarty_tpl->tpl_vars['itemdata']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
            <div class="pricing_bundle_item">
                <div class="holder">
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['pid']) {?>
                        <div class="bundle_item_name">
                            Product Name: <?php echo $_smarty_tpl->tpl_vars['item']->value['item_name'];?>

                        </div>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['billingcycle']) {?>
                        <div class="bundle_item_name">
                            Billing Cycle: <?php echo $_smarty_tpl->tpl_vars['item']->value['billingcycle'];?>

                        </div>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['price']) {?>
                        <div class="bundle_item_name">
                            Price: <?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>

                        </div>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['tlds']) {?>
                        <div class="bundle_item_name">
                            <span>TLDs:</span>
                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['tlds'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['tld'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['tld']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['tld']->value) {
$_smarty_tpl->tpl_vars['tld']->_loop = true;
$foreach_tld_Sav = $_smarty_tpl->tpl_vars['tld'];
?>
                                <span><?php echo $_smarty_tpl->tpl_vars['tld']->value;?>
</span>
                            <?php
$_smarty_tpl->tpl_vars['tld'] = $foreach_tld_Sav;
}
?>
                        </div>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['price']) {?>
                        <div class="bundle_item_name">
                            Registration Period: <?php echo $_smarty_tpl->tpl_vars['item']->value['regperiod'];?>

                        </div>
                    <?php }?>
                </div>
            </div>
        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
    </div>
    <div class="pricing_bundle_submit">
        <div class="holder">
            <a class="whmpress_order_button" href="<?php echo $_smarty_tpl->tpl_vars['order_link']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['button_text']->value;?>
</a>
        </div>
    </div>
</div>  <!-- /.price-bundle --><?php }
}
?>