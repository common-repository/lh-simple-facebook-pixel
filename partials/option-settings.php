<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form method="post" action="" >	

<input type="hidden" name="<?php echo $this->hidden_field_name; ?>" value="Y" />

<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->enable_pixel_field; ?>"><?php _e("Enable Facebook Pixel:", $this->namespace ); ?></label></th>
<td><select name="<?php echo $this->enable_pixel_field; ?>" id="<?php echo $this->enable_pixel_field; ?>">'
<option value="1" <?php if ($this->options[$this->enable_pixel_field] == 1){ echo 'selected="selected"'; } ?> >Yes</option>
<option value="0" <?php if ($this->options[$this->enable_pixel_field] == 0){ echo 'selected="selected"'; } ?> >No</option>
</select> - Set this to yes if you want too use email addresses instead of usernames to log in.</td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->pixel_id_field; ?>"><?php _e("Facebook Pixel ID(s);", $this->namespace ); ?></label></th>

<?php if (is_array($this->options[ $this->pixel_id_field ])){ $pixel_var = implode (',', $this->options[ $this->pixel_id_field ]); } else { $pixel_var = $this->options[ $this->pixel_id_field ];  }  ?>
<td><input type="text" name="<?php echo $this->pixel_id_field; ?>" id="<?php echo $this->pixel_id_field; ?>" value="<?php echo $pixel_var; ?>" size="10" />
<p><?php _e("For multiple pixels enter as a comma separated ids (no spaces)", $this->namespace ); ?></p></td>
</tr>
</table>
<?php submit_button( 'Save Changes' ); ?>
</form>