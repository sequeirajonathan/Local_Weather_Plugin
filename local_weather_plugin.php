<?php
/*
Plugin Name: Local Weather Plugin
Plugin URI: 
Description: Displays local weather based on city
Version: 1.0.0
Author: Jonathan Sequeira
Author URI: http://www.vet2dev.com
 */

function wp_local_weather_menu()
{
    add_options_page(
        'My Weather Plugin',
        'Local Weather Settings',
        'manage_options',
        'local_weather_plugin',
        'wpweather_get_profile'
    );
}
add_action('admin_menu', 'wp_local_weather_menu');


function wpweather_get_profile()
{
  
    echo ('
    <style> 
    @import "https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css";

    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    .ui.button, .ui.buttons .button, .ui.buttons .or {
        padding-bottom: 1.3vw !important;
    }
    </style>

    <div class="form-container" style="margin: 4vh auto;">
    <form method="post" class="ui form">
        <div class="field" style="width: 25%;">
        <label>Zip Code</label>
        <input type="text" name="zip" placeholder="Zip Code">
        </div>
    <button class="ui primary button" type="submit">Submit</button>
    </form>
    </div>

');


    $zip = $_POST["zip"] ;
    $api_url = 'https://api.apixu.com/v1/current.json?key=e0a8080b3f0b46fab2d200351191902&q='. $zip;
    $args = array('timeoute' => 120);
    $get_request = wp_remote_get($api_url, $args);
    $response = json_decode($get_request['body']);
    $f = $response->current->temp_f;
    $c = $response->current->temp_c;
    $icon = $response->current->condition->icon;

    ?>
    
      <div class="container">
          <h1> WEATHER CONDITIONS</h1>
          <p><?php echo $response->current->condition->text; ?></p>
          <?php if(isset($icon)){
              echo '<img src="'.$icon.'"/>';
          }?>
          
      </div>
      
      <table class="ui celled table">
  <thead>
    <th>City</th>
    <th>Region</th>
    <th>Temperature</th>
    <th>Winds</th>
    <th>Humidity</th>
  </tr></thead>
  <tbody>
    <tr>
      <td data-label="City"><?php echo $response->location->name ?></td>
      <td data-label="Region"><?php echo $response->location->region ?></td>
      <td data-label="Temperature"><?php echo $f ?>&#8457; / <?php echo $c ?>&#8451;</td>
      <td data-label="Winds"><?php echo $response->current->wind_mph; ?>/MPH</td>
      <td data-label="Humidity"><?php echo $response->current->humidity; ?>%</td>
    </tr>
  </tbody>
</table>
    <?php
  
   return $response;
}
add_action('wp_enqueue_scripts', 'wpweather_get_profile');