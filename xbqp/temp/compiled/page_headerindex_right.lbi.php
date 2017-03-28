<?php

function get_car_system_brands1($cat = 0, $app = 'brand')
{
    $children = ($cat > 0) ? ' AND ' . get_car_system_children($cat) : '';

    $sql = "SELECT b.brand_id, b.brand_name, b.brand_logo, b.brand_desc, COUNT(*) AS goods_num, IF(b.brand_logo > '', '1', '0') AS tag ".
            "FROM " . $GLOBALS['ecs']->table('brand') . "AS b, ".
                $GLOBALS['ecs']->table('goods') . " AS g ".
            "WHERE g.brand_id = b.brand_id $children AND is_show = 1 " .
            " AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            "GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY tag DESC, b.sort_order ASC";
    $row = $GLOBALS['db']->getAll($sql);

    foreach ($row AS $key => $val)
    {
        $row[$key]['url'] = build_uri($app, array('cid' => $cat, 'bid' => $val['brand_id']), $val['brand_name']);
        $row[$key]['brand_desc'] = htmlspecialchars($val['brand_desc'],ENT_QUOTES);
    }

    return $row;
}
?>
<div id="shiyin" class="all_cat catright" style="float: left; background: #ffffff;filter: alpha(Opacity=80);background-color: rgba(255,255,255,.8);">

    <?php $_from = $this->_var['car_system_categories_all']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat_0_15649100_1490613710');$this->_foreach['cat0'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat0']['total'] > 0):
    foreach ($_from AS $this->_var['cat_0_15649100_1490613710']):
        $this->_foreach['cat0']['iteration']++;
?>
<?php if ($this->_foreach['cat0']['iteration'] < 9): ?>
    <div class="list xj_center" onmouseover="_show_(this,{'source':'JS_side_cat_textarea_<?php echo $this->_foreach['cat0']['iteration']; ?>','target':'JS_side_cat_list_<?php echo $this->_foreach['cat0']['iteration']; ?>'});" onmouseout="_hide_(this);">
	<dl class="cat" style="width: 194px!important;right:5px;left: -1px; <?php if (($this->_foreach['cat0']['iteration'] == $this->_foreach['cat0']['total']) || $this->_foreach['cat0']['iteration'] == 9): ?>border:none<?php endif; ?> transform: rotateY(180deg);" >
  		<dt class="catName"> 
        	<strong class="cat<?php echo $this->_foreach['cat0']['iteration']; ?> Left" >
            	<a href="<?php echo $this->_var['cat_0_15649100_1490613710']['url']; ?>"  style=" -moz-transform:none;-webkit-transform:none;-o-transform:none;-ms-transform:none;transform:none;position: absolute; transform: rotateY(180deg);" target="_blank" title="进入<?php echo $this->_var['cat_0_15649100_1490613710']['name']; ?>频道"><?php echo $this->_var['cat_0_15649100_1490613710']['name']; ?></a>
            </strong>
    		<p style=" -moz-transform:none;-webkit-transform:none;-o-transform:none;-ms-transform:none;transform:none; transform: rotateY(180deg);"> 
      		<?php $_from = $this->_var['cat_0_15649100_1490613710']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_15749100_1490613710');$this->_foreach['namechild'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['namechild']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_15749100_1490613710']):
        $this->_foreach['namechild']['iteration']++;
?> 
      			<a href="<?php echo $this->_var['child_0_15749100_1490613710']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['child_0_15749100_1490613710']['name']); ?>"><?php echo htmlspecialchars($this->_var['child_0_15749100_1490613710']['name']); ?></a> 
      		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    		</p>
  		</dt>
	</dl>
	
	<div id="JS_side_cat_list_<?php echo $this->_foreach['cat0']['iteration']; ?>" class="hideMap Map_positon<?php echo $this->_foreach['cat0']['iteration']; ?> xj_center" style="left: -573px;width: 570px; ">		<div class="topMap clearfix">
			<div class="subCat clearfix">

            <?php $_from = $this->_var['cat_0_15649100_1490613710']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_15749100_1490613710');$this->_foreach['namechild'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['namechild']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_15749100_1490613710']):
        $this->_foreach['namechild']['iteration']++;
?>
                    <div class="list1 clearfix" <?php if (($this->_foreach['namechild']['iteration'] == $this->_foreach['namechild']['total'])): ?>style="border:none"<?php endif; ?>>
					<div class="cat1">
                        <a href="<?php echo $this->_var['child_0_15749100_1490613710']['url']; ?>" target="_blank" title=<?php echo htmlspecialchars($this->_var['child_0_15749100_1490613710']['name']); ?>"><?php echo htmlspecialchars($this->_var['child_0_15749100_1490613710']['name']); ?>：</a>
                    </div>
					<div class="link1">
                           
                    <?php $_from = $this->_var['child_0_15749100_1490613710']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'childer_0_15749100_1490613710');$this->_foreach['childername'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childername']['total'] > 0):
    foreach ($_from AS $this->_var['childer_0_15749100_1490613710']):
        $this->_foreach['childername']['iteration']++;
?>       
                        <a href="<?php echo $this->_var['childer_0_15749100_1490613710']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['childer_0_15749100_1490613710']['name']); ?>"><?php echo htmlspecialchars($this->_var['childer_0_15749100_1490613710']['name']); ?></a>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>     
                         
        			</div>
				</div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

			</div>
			<div class="subBrand">
               <?php
	 $cat_info = get_cat_info_ex($GLOBALS['smarty']->_var['cat']['id']);

	$GLOBALS['smarty']->assign('index_image',get_advlist('导航菜单-'.$cat_info['cat_id'].'-右侧-促销专题', 5));
	  ?>
             <?php if ($this->_var['index_image']): ?>
              <dl class="categorys-promotions">
                <dt>促销活动</dt>
                <dd>
                  <ul>
				  <?php $_from = $this->_var['index_image']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['index_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['index_image']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['index_image']['iteration']++;
?>
                    <li><a target="_blank" href="<?php echo $this->_var['ad']['url']; ?>"><img src="<?php echo $this->_var['ad']['image']; ?>"></a></li>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                  </ul>
                </dd>
              </dl>
			  <?php endif; ?>
        <?php if (0): ?>
              <dl class="categorys-brands">
                 <dt>推荐品牌</dt>
                 <dd>
                 	<ul>
                        <?php $_from = get_car_system_brands1($GLOBALS[smarty]->_var[cat][id]); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'bchilder');if (count($_from)):
    foreach ($_from AS $this->_var['bchilder']):
?>
                        <li><a target="_blank" href="<?php echo $this->_var['bchilder']['url']; ?>"><?php echo htmlspecialchars($this->_var['bchilder']['brand_name']); ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                  </dd>
                </dl>
                <?php endif; ?>
              </div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
 

</div>
