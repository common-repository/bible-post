<?php
$version = $instance['version'];

$showVersion = $instance["showVersion"];
$show_version_no = $showVersion == 0 ? 'checked' : '';
$show_version_yes = $showVersion == 1 ? 'checked' : '';

$citaBiblica = $instance['citaBiblica'];

?>

<p>
<b><?php _e('Titulo:', 'bible-post') ?></b><br />
<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
</p>

<p>
<b><?php _e('Cita Biblica:', 'bible-post') ?></b><br />
<input id="<?php echo $this->get_field_id( 'citaBiblica' ); ?>" name="<?php echo $this->get_field_name( 'citaBiblica' ); ?>" value="<?php echo $instance['citaBiblica']; ?>" />
</p>

<p>
<b><?php _e('Version:', 'bible-post') ?></b><br />
<select id="<?php echo $this->get_field_id( 'version' ); ?>" name="<?php echo $this->get_field_name( 'version' ); ?>">
<?php
foreach ($this->versions as $num => $name) {
  $sel = $version == $num ? 'selected' : '';
  echo '<option value="'.$num.'" '.$sel.'>'.$name.'</option>'."\r\n";
}
?>
</select>
  </p>

<p>
<strong><?php _e('Mostrar Version?', 'bible-post') ?></strong>
<input type=radio name="<?php echo $this->get_field_name( 'showVersion' ); ?>" value="1" <?php echo $show_version_yes; ?> /> <?php _e('Si', 'bible-post'); ?>
<input type=radio name="<?php echo $this->get_field_name( 'showVersion' ); ?>" value="0" <?php echo $show_version_no; ?> /> <?php _e('No', 'bible-post'); ?>
<br /><em><?php _e('Mostrara un Acronimo (si existe) despues de la referencia biblica', 'bible-post') ?></em>
</p>
