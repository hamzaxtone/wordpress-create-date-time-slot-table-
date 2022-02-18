<?php


//making the meta box (Note: meta box != custom meta field)
function wpse_add_custom_meta_box_2() {
   add_meta_box(
       'custom_meta_box-2',       // $id
       'Calendar Details',                  // $title
       'show_custom_meta_box_2',  // $callback
       'lp_course',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
}
add_action('add_meta_boxes', 'wpse_add_custom_meta_box_2');
//showing custom form fields
function show_custom_meta_box_2() {
    global $post;

    // Use nonce for verification to secure data sending
    wp_nonce_field( basename( __FILE__ ), 'wpse_our_nonce' );

    ?>
	<input type="hidden" id="custom-calendar" name="custom-calendar" value="" />
	<ul class="calendar-fields">
		<span class="add-calendar">Add</span>
		<span class="remove-calendar">Remove</span>
		<div class="form-field">
			<label>Date <input type="date" name="cal-date"></label>
			<label>Slot <select name="cal-slot"></select></label>
			<label>Live <input type="text" name="cal-date"></label>
			<input type="number" name="wpse_value" value="">
		</div>
	</ul>
	
	<script>
	/*
	var arr = [];
	var formValues = JSON.parse(arr);
	formValues.push({
        date: assa,
        slot: keke
    });
function removeDesign(val,index){
    var obj = JSON.parse(val);
  	obj.splice( index,1 );
    //localStorage.formValues = JSON.stringify(obj);
    getDesigns(localStorage.formValues);
    
}
function getDesigns(val){
	$('.designs').empty();
    var obj = JSON.parse(val);
    console.log(obj);
    var i = 0;
    obj.forEach(function(object) {
  		$('.calendar-fields').append('<li data-index="'+i+'"><i class="fa fa-remove" data-index="'+i+'"></i><a href="'+object.src+'" download="my-wheel-design" target="_blank"><img src="'+object.src+'" /></a></li>');
		i++;
    });  	
}

	*/
	</script>
    <?php
}
//now we are saving the data
function wpse_save_meta_fields( $post_id ) {

  // verify nonce
  if (!isset($_POST['wpse_our_nonce']) || !wp_verify_nonce($_POST['wpse_our_nonce'], basename(__FILE__)))
      return 'nonce not verified';

  // check autosave
  if ( wp_is_post_autosave( $post_id ) )
      return 'autosave';

  //check post revision
  if ( wp_is_post_revision( $post_id ) )
      return 'revision';

  // check permissions
  if ( 'project' == $_POST['post_type'] ) {
      if ( ! current_user_can( 'edit_page', $post_id ) )
          return 'cannot edit page';
      } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
          return 'cannot edit post';
  }

  //so our basic checking is done, now we can grab what we've passed from our newly created form
  $wpse_value = $_POST['wpse_value'];
  
  echo $wpse_value;
  
  
  

  //simply we have to save the data now
  /* global $wpdb;
  $table = $wpdb->base_prefix . 'project_bids_mitglied';
  $wpdb->insert(
            $table,
            array(
                'col_post_id' => $post_id, //as we are having it by default with this function
                'col_value'   => intval( $wpse_value ) //assuming we are passing numerical value
              ),
            array(
                '%d', //%s - string, %d - integer, %f - float
                '%d', //%s - string, %d - integer, %f - float
              )
          ); */ 

} 
add_action( 'save_post', 'wpse_save_meta_fields' );
add_action( 'new_to_publish', 'wpse_save_meta_fields' );


add_shortcode('view-calendar','view_calendar');
function view_calendar(){
	
	$classes = array();
	$args = array(
		'post_type'        => 'lp_course',
		'posts_per_page'   => -1,
		//'category'         => '',
	);
	$query = new WP_Query( $args ); 
		if ( $query->have_posts() ) {
			$class = array();
		while ( $query->have_posts() ) {
		$query->the_post(); 
		
			$classes[] = array( get_the_ID(),get_the_title() );
			
		} // end while
	} // end if
	wp_reset_query();
	
	
	
		//Sample time loop.
        $time = new DateTime('2022-01-01 01:00:00');
        $end = new DateTime('2022-01-01 23:59:00');
		$slots = array();

        while ($time < $end) {

            //echo $stamp = $time->format('h:i a') . '</br>';
			$time_slot .= "<option>".$time->format('h:i a')."</option>"; 
			$slots[] = $time->format('h:i a');
            $time->add(new DateInterval('PT' . 30 . 'M'));
        }
		
	$begin = new DateTime( "2022-01-01" );
	$end   = new DateTime( "2022-03-31" );
	?>
	
<style>
table{
	margin-bottom:0px;
}
.slot-table{
	/* padding-left:15px; */
}
.customtable * {
	font-family:Poppins !important;
}
.customtable tr.date > td h6{
	font-size:16px;
}
.customtable tr.date > td h6 span{
	font-weight: 600;
    font-size: 18px;
    text-transform: uppercase;
}
.customtable tr.date > td {
    background: #fff;
    border-bottom: 1px solid #e1e1e1;
	padding-left:0px;
	padding-right:0px;
	padding-top:20px;
	padding-bottom:0px;
}
.customtable thead th{
	text-align:center;
}
.customtable tr.date > td td{
	padding-left:7px;
	padding-right:7px;
	padding-top:5px;
	padding-bottom:5px;
	background:#f3f3f3;
	text-align:center;
}
.customtable tr.date > td td .btn{
	padding:5px 10px;
	font-size:11px;
}
.customtable tr.date > td tr:nth-child(even) td{
	background:#f9f9f7;
}
.customtable tr.date > td td .slot_title{
	white-space:nowrap;
	margin-right:3px;
}
.customtable tr.date > td td span{
	font-size:13px;
	line-height: 18px;
    display: block;
}
.customtable tr.date > td td .slot_additonal_text{
	font-size:12px;
	text-align:left;
}
.customtable h6{
	margin-bottom:0px;
	padding-left:7px;
	padding-right:7px;
}
.customtable tr.date tr :is(select,input){
	max-width:100%;
	width: 100%;
}
.d-flex{
	display:flex;
}
form{
	opacity:0;
	visibility:hidden;
	height:0px;
	max-height:0px;
	transform:scale(0);
}
input.cal-date {
    font-weight: bold;
    color: #000;
    font-size: 16px;
    background: #fff;
}

.customtable tr th {
    font-size: 16px !important;
    color: #000 !important;
    font-weight: 500 !important;
    text-transform: uppercase;
    border: 1px solid #e1e1e1;
}
.customtable {
    position: relative;
    /* max-height: 80vh;
    overflow: auto;
    min-height: 700px; */
}

table.mainTable {
  display: inline-grid;
  grid-template-areas: 
  "head-fixed" 
  "body-scrollable";
}

thead.mainTable-Head {
  grid-area: head-fixed;
  display:table;
}
thead.mainTable-Head th {
    width: 20%;
}
thead.mainTable-Head th:nth-child(1),thead.mainTable-Head th:nth-child(2),
.customtable tr.date > td td:nth-child(1),.customtable tr.date > td td:nth-child(2){
	width: 25%;
}
thead.mainTable-Head th:nth-child(4),thead.mainTable-Head th:nth-child(5){
	width: 17%;
    padding-left: 10px;
    padding-right: 10px;
}

tbody.mainTable-Body {
  grid-area: body-scrollable;
  overflow: auto;
  max-height: 80vh;
}
.justify-space{
	justify-content:space-between;
}
.align-center{
	align-items:center;
}

span.show-desc {
    position: relative;
	cursor:pointer;
}
span.show-desc img{
	margin-bottom:10px;
}
.show-desc .tooltip-desc {
    position: absolute;
    left: 0;
    top: 100%;
    /* right: 0; */
    margin: 0 auto;
    z-index: 3;
    width: 500px;
    max-width: 100%;
    background: #fff;
    padding: 10px;
    box-shadow: 0 0 25px #cfcfcf;
    border-radius: 10px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.5s ease;
}

.show-desc:hover .tooltip-desc {
    opacity: 1;
    visibility: visible;
}

.show-desc:hover {
    color: #5f3b92;
}
.calendar-wrap{
	padding: 20px;
    box-shadow: 0 0 20px #ddd;
}
</style>
<div class="wrap calendar-wrap">
<h1 class="wp-heading-inline">View All Classes Schedule</h1>
<div class="customtable">
<table class="wp-list-table widefat fixed striped table table-view-list mainTable">
	<thead class="mainTable-Head">
		<tr>
			<th>Date</th>
			<th>Classes</th>
			<th>Teacher</th>
			<th>2nd Instructor</th>
			<th>Duration</th>
		</tr>
	</thead> 
	<tbody class="mainTable-Body">
<?php
	if( get_option('classes_calendar') && !empty(get_option('classes_calendar')) ){
	$rowData = get_option('classes_calendar');
	//echo "<pre>";print_r(json_decode($rowData, true));echo "</pre>";
	$rowData = json_decode($rowData, true);
	$tr = '';
	foreach( $rowData as $key => $val ){
		//echo $val['date'];
		
		//$date = $val["date"]->format("D M Y-m-d");
		$date = date_create($val["date"]);
		$date = '<span>'.date_format($date,"D").'</span>'.date_format($date," M d,Y");
		
		$date_now = date("Y-m-d");
		
		$date_now = time(); //current timestamp
		$date_convert = strtotime($val["date"]);
		
		$trSlot = '';
		$show_link = "";
		foreach( $val["slots"] as $keySLOT => $valSLOT ){
			
			$show_link = "";
			if( $date_now <= $date_convert ){
				$show_link = "<a data-date_now='".$date_now."' data-date='".$val["date"]."' href='".get_the_permalink($valSLOT["courseID"])."?date=".$val["date"]."&slot=".$valSLOT["slot"]."' class='btn btn-small btn-style-1'>Sign Up</a>";
			}
			
			$slotOPTION = '';
			foreach( $slots as $slot_KEY => $slot_VAL ){
				$selected_SLOT = 'not-selected';
				if( $valSLOT["slot"] == $slot_VAL ){
					$selected_SLOT = 'selected';
				}
				$slotOPTION .= '
				<option '.$selected_SLOT.' value="'.$slot_VAL.'">'.$slot_VAL.'</option>
				';
			}
			
			$classOPTION = '';
			foreach( $classes as $class_KEY => $class_VAL ){
				$selected_CLASS = 'not-selected';
				if( $valSLOT["courseID"] == $class_VAL[0] ){
					$selected_CLASS = 'selected';
				}
				$classOPTION .= '
				<option '.$selected_CLASS.' value="'.$class_VAL[0].'">'.$class_VAL[1].'</option>
				';
			}
			
			$IMGurl = wp_get_attachment_url( get_post_thumbnail_id( $valSLOT["courseID"] ), 'thumbnail' );
			
			$show_desc = '<div class="tooltip-desc">
			<h5>Description</h5>
			<p><img src="'.$IMGurl.'" />'.get_the_excerpt($valSLOT["courseID"]).'</p>
			
			</div>';
			
			$trSlot .= '
			<tr class="row-index" id="R'.$keySLOT.'">
				<td><div class="d-flex flex-wrap justify-space- align-start"><span class="slot_title" title="'.$valSLOT["slot"].'">'.$valSLOT["slot"].' CST</span> <span class="slot_additonal_text">'.$valSLOT["slot_additonal_text"].'</span> '.$show_link.'</div></td>
				<td><span class="show-desc" title="'.$valSLOT["course"].'">'.$valSLOT["course"].' '.$show_desc.'</span></td>
				<td><span title="'.$valSLOT["teacher"].'">'.$valSLOT["teacher"].'</span></td>
				<td><span title="'.$valSLOT["instructor"].'">'.$valSLOT["instructor"].'</span></td>
				<td><span title="'.$valSLOT["duration"].'">'.$valSLOT["duration"].'</span>
				</td>
			</tr>
			';
		}
		
		
		
		$empty = "empty";
		if(!empty($trSlot)){
			$empty = "not-empty";
		}
		$tr .= '
		<tr data-index="'.$key.'" class="date custom '.$empty.'">
		<td colspan="5">
		<h6>'.$date.'</h6>
		
			<table data-index="'.$key.'" class="wp-list-table widefat fixed striped table-view-list slot-table">
				<tbody>
					'.$trSlot.'
				</tbody>
			</table>
		</td>
		</tr>
		';
	}
}
echo $tr;


?>
</tbody>
</table>
</div>
</div>
<?php

}
/* SHORTCODE END */


add_action( 'admin_action_wpse10500', 'wpse10500_admin_action' );
function wpse10500_admin_action()
{
    // Do your stuff here
	
	//print_r($_POST);
    //wp_redirect( $_SERVER['HTTP_REFERER'] );
    //exit();
}
add_action( 'admin_menu', 'wpse10500_admin_menu' );
function wpse10500_admin_menu()
{
    add_management_page( 'WPSE 10500 Test page', 'WPSE 10500 Test page', 'administrator', 'wpse10500', 'wpse10500_do_page' );
}
function wpse10500_do_page()
{
	$classes = array();
	$args = array(
		'post_type'        => 'lp_course',
		'posts_per_page'   => -1,
		//'category'         => '',
	);
	$query = new WP_Query( $args ); 
		if ( $query->have_posts() ) {
			$class = array();
		while ( $query->have_posts() ) {
		$query->the_post(); 
		
			$classes[] = array( get_the_ID(),get_the_title() );
			
		} // end while
	} // end if
	wp_reset_query();
	
	
	
		//Sample time loop.
        $time = new DateTime('2022-01-01 01:00:00');
        $end = new DateTime('2022-01-01 23:59:00');
		$slots = array();

        while ($time < $end) {

            //echo $stamp = $time->format('h:i a') . '</br>';
			$time_slot .= "<option>".$time->format('h:i a')."</option>"; 
			$slots[] = $time->format('h:i a');
            $time->add(new DateInterval('PT' . 15 . 'M'));
        }
		
		
	//echo $begin = date("Y-m-d");
	$begin = new DateTime( "2022-01-31" );
	$end   = new DateTime( "2022-03-31" );
	
	//echo "<pre>";print_r($classes);echo "</pre>";
	//echo "<br><br>".json_encode($slots, JSON_FORCE_OBJECT);
?>
<script>

var slots = [<?php echo json_encode($slots,JSON_FORCE_OBJECT) ?>];
var classes = [<?php echo json_encode($classes,JSON_FORCE_OBJECT) ?>];
//console.log(  slots  );
/* slots.forEach((element) => {
    console.log(element);
}); */
var slot = "<option>Select Slot</option>";
jQuery.each(slots[0], function (key, value) {
//json_arr.push("key" + "(" + key + ")" + " . " + value.name + "<br>");
slot += "<option value='"+value+"'>"+value+"</option>";	
});

var course = "<option>Select Class</option>";
jQuery.each(classes[0], function (key, value) {
	//console.log(value[1]);
	course += "<option value='"+value[0]+"'>"+value[1]+"</option>";	
});


</script>


<style>
.slot-table{
	/* padding-left:15px; */
}
.customtable tr.date > td {
    background: #e1e1e1;
    border-bottom: 2px solid #fff;
}
.customtable tr.date tr :is(select,input){
	max-width:100%;
	width: 100%;
}
.d-flex{
	display:flex;
}
form{
	opacity:0;
	visibility:hidden;
	height:0px;
	max-height:0px;
	transform:scale(0);
}
input.cal-date {
    font-weight: bold;
    color: #000;
    font-size: 16px;
    background: #fff;
}

.customtable tr th {
    font-size: 18px !important;
    color: #000 !important;
    font-weight: bold !important;
    text-transform: uppercase;
    border: 1px solid #e1e1e1;
}
.customtable {
    position: relative;
    /* max-height: 80vh;
    overflow: auto;
    min-height: 700px; */
}

table.mainTable {
  display: inline-grid;
  grid-template-areas: 
  "head-fixed" 
  "body-scrollable";
}

thead.mainTable-Head {
  grid-area: head-fixed;
  display:table;
}
thead.mainTable-Head th {
    width: 20%;
}

tbody.mainTable-Body {
  grid-area: body-scrollable;
  overflow: auto;
  max-height: 80vh;
}
</style>
<div class="wrap">
<h1 class="wp-heading-inline">Manage Calendar</h1>
<div class="customtable">
<table class="wp-list-table widefat fixed striped table-view-list mainTable">
	<thead class="mainTable-Head">
		<tr>
			<th>Date</th>
			<th>Classes</th>
			<th>Teacher</th>
			<th>2nd Instructor</th>
			<th>Duration</th>
		</tr>
	</thead> 
	<tbody class="mainTable-Body">
<?php
/**

**/

if( get_option('classes_calendar') && !empty(get_option('classes_calendar')) ){
	$rowData = get_option('classes_calendar');
	//echo "<pre>";print_r(json_decode($rowData, true));echo "</pre>";
	$rowData = json_decode($rowData, true);
	$tr = '';
	foreach( $rowData as $key => $val ){
		//echo $val['date'];
		$trSlot = '';
		foreach( $val["slots"] as $keySLOT => $valSLOT ){
			
			$slotOPTION = '';
			foreach( $slots as $slot_KEY => $slot_VAL ){
				$selected_SLOT = 'not-selected';
				if( $valSLOT["slot"] == $slot_VAL ){
					$selected_SLOT = 'selected';
				}
				$slotOPTION .= '
				<option '.$selected_SLOT.' value="'.$slot_VAL.'">'.$slot_VAL.'</option>
				';
			}
			
			$classOPTION = '';
			foreach( $classes as $class_KEY => $class_VAL ){
				$selected_CLASS = 'not-selected';
				if( $valSLOT["courseID"] == $class_VAL[0] ){
					$selected_CLASS = 'selected';
				}
				$classOPTION .= '
				<option '.$selected_CLASS.' value="'.$class_VAL[0].'">'.$class_VAL[1].'</option>
				';
			}
			
			
			
			$trSlot .= '
			<tr class="row-index" id="R'.$keySLOT.'">
				<td class="d-flex">
					<button class="button btn btn-danger remove-slot" type="button">X</button>
					<!--<input readonly disabled type="text" value="'.$valSLOT["slot"].'" />-->
					<select class="slot" name="slot[]">'.$slotOPTION.'</select>
					<input type="text" class="slot_additonal_text" name="slot_additonal_text[]" placeholder="Extra Description" value="'.$valSLOT["slot_additonal_text"].'" />
				</td>
				<td>
				<!--<input readonly disabled type="text" value="'.$valSLOT["course"].'" />-->
					<select class="course" name="course[]">'.$classOPTION.'</select>
				</td>
				<td><input type="text" class="teacher" name="teacher[]" value="'.$valSLOT["teacher"].'" /></td>
				<td><input type="text" class="instructor" name="instructor[]" value="'.$valSLOT["instructor"].'" /></td>
				<td>
					<input type="text" class="duration" name="duration[]" value="'.$valSLOT["duration"].'" />
				</td>
			</tr>
			';
		}
		$tr .= '
		<tr data-index="'.$key.'" class="date custom">
		<td colspan="5">
			<input readonly disabled type="text" class="cal-date" name="cal-date[]" value="'.$val["date"].'" />
			<button data-index="'.$key.'" class="button button-primary add-slot">Add Slot</button>
			<table data-index="'.$key.'" class="wp-list-table widefat fixed striped table-view-list slot-table">
				<tbody>
					'.$trSlot.'
				</tbody>
			</table>
		</td>
		</tr>
		';
	}
}else{
	$index = 0;
	for($i = $begin; $i <= $end; $i->modify('+1 day')){
		$date = $i->format("Y-m-d");?> 
		<tr data-index="<?=$index;?>" class="date">
			<td colspan="5">
				<input readonly disabled type="text" class="cal-date" name="cal-date[]" value="<?=$date;?>" />
				<button data-index="<?=$index;?>" class="button button-primary add-slot">Add Slot</button>
				<table data-index="<?=$index;?>" class="wp-list-table widefat fixed striped table-view-list slot-table">
					<tbody>
						<!--<tr class="">
							<td>
								<select>
									<?=$time_slot;?>
								</select>
							</td>
							<td>c</td>
							<td>t</td>
							<td>i</td>
							<td>d</td>
						</tr>-->
					</tbody>
				</table>
			</td>
		</tr>
		<?php
		$index++;
	}
}
echo $tr;


?>
</tbody>
</table>
<?php

?>

<p class="submit">
<button class="button button-primary serialize">Save</button>
</p>
</div>
</div>
<script>
jQuery('.notice-warning').remove();
var rowIdx = 0;
	jQuery(document).on('click','.remove-slot',function(){
		jQuery(this).parent().parent().remove();
	});
	jQuery('.add-slot').on('click',function(){
		var index = jQuery(this).attr('data-index');
		jQuery('.slot-table[data-index="'+index+'"] tbody').append(`
			<tr class="row-index" id="R${++rowIdx}">
				<td class="d-flex">
					<button class="button btn btn-danger remove-slot" type="button">X</button>
					<select class="slot" name="slot[]">`+slot+`</select>
					<input type="text" class="slot_additonal_text" name="slot_additonal_text[]" placeholder="Extra Description" />
				</td>
				<td><select class="course" name="course[]">`+course+`</select></td>
				<td><input type="text" class="teacher" name="teacher[]" value="LUCINDA" /></td>
				<td><input type="text" class="instructor" name="instructor[]"/></td>
				<td>
					<input type="text" name="duration[]" class="duration" />
				</td>
		   </tr>`);
	});
</script>
<script>


	jQuery(document).on('click','.serialize',function(){
		var rr = [];
		jQuery('tr.date').each(function(i, items_list){
			
			var rowSlots = [];
			jQuery(items_list).find('.row-index').each(function(){
				rowSlots.push({
					slot: jQuery(this).find('.slot').val(),
					courseID: jQuery(this).find('.course option:selected').val(),
					course: jQuery(this).find('.course option:selected').text(),
					slot_additonal_text: jQuery(this).find('.slot_additonal_text').val(),
					teacher: jQuery(this).find('.teacher').val(),
					instructor: jQuery(this).find('.instructor').val(),
					duration: jQuery(this).find('.duration').val()
				});
			});
			//console.log(rowSlots);
			rr.push({
				date: jQuery(this).find('input.cal-date').val(),
				slots: rowSlots
			});
			//console.log(rowSlots);
		});
		
		//console.log(rr);
		jQuery('#classes_calendar').val(JSON.stringify(rr));
		setTimeout(function(){
			jQuery('#submit').trigger('click');
		},1500);
	});
</script>
    <!--<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
        <input type="hidden" name="action" value="wpse10500" />
        <input type="submit" value="Do it!" />
    </form>-->
	
	<form method="POST" action="options.php">
    <?php
    settings_fields( 'wpse10500' );
    do_settings_sections( 'wpse10500' );
    submit_button();
    ?>
    </form>
<?php


} 






























/**
function my_admin_menu() {
    add_menu_page(
        __( 'Sample page', 'my-textdomain' ),
        __( 'Sample menu', 'my-textdomain' ),
        'manage_options',
        'sample-page',
        'my_admin_page_contents',
        'dashicons-schedule',
        3
    );
}
add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_page_contents() {
    ?>
    <h1> <?php esc_html_e( 'Welcome to my custom admin page.', 'my-plugin-textdomain' ); ?> </h1>
    <form method="POST" action="options.php">
    <?php
    settings_fields( 'wpse10500' );
    do_settings_sections( 'wpse10500' );
    submit_button();
    ?>
    </form>
    <?php
} **/


add_action( 'admin_init', 'my_settings_init' );

function my_settings_init() {

    add_settings_section(
        'sample_page_setting_section',
        __( 'Save Your Calendar', 'my-textdomain' ),
        'my_setting_section_callback_function',
        'wpse10500'
    );

		add_settings_field(
		   'classes_calendar',
		   __( '', 'my-textdomain' ),
		   'my_setting_markup',
		   'wpse10500',
		   'sample_page_setting_section'
		);

		register_setting( 'wpse10500', 'classes_calendar' );
}


function my_setting_section_callback_function() {
    //echo '<p>Intro text for our settings section</p>';
}


function my_setting_markup() {
    ?>
    <!--<label for="classes_calendar"><?php //_e( 'My Input' ); ?></label>-->
    <!--<input type="hidden" id="classes_calendar" name="classes_calendar" value="<?php //echo get_option( 'classes_calendar' ); ?>">-->
	<textarea id="classes_calendar" name="classes_calendar"><?php echo get_option( 'classes_calendar' ); ?></textarea>
    <?php
}
?>