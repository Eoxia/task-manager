<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<script>
var list_task = {
  'type': 'listbox',
  'name': 'task_id',
  'label': "<?php _e( 'Task', 'task-manager' ); ?>",
  'values': []
};

<?php
if ( !empty( $list_task ) ):
  foreach ( $list_task as $element ):
    ?>
    list_task.values.push(
      {
        "text": "<?php echo '#' . $element->id . ' - ' . $element->title; ?>",
        "value": "<?php echo $element->id; ?>"
      }
    );
    <?php
  endforeach;
endif;
?>
</script>
