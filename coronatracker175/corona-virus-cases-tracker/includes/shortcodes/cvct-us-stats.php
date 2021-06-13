<?php

class CVCT_US_Stats_Shortcode
{

function __construct() {
    //main plugin shortcode for list widget
    require_once CVCT_DIR . 'includes/cvct-get-country-name.php';
    add_shortcode( 'cvct-us-stats', array($this, 'cvct_us_stats_shortcode' ));
    
}
/*
|--------------------------------------------------------------------------
| Countries data shortcodee
|--------------------------------------------------------------------------
*/ 
public function  cvct_us_stats_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'id'=>'',
        'title'=>'COVID-19 U.S. Spread Data',
        'show-list'=>'yes',
     ), $atts, 'cvct-charts' );
     $title = !empty($atts['title'])?$atts['title']:'COVID-19 U.S. Spread Data';
     $show_list=!empty($atts['show-list'])?$atts['show-list']:"yes";
     if(!is_admin()){
      $this->cvct_map_load_assets($show_list);
      }
      $allData = cvct_get_us_states_data_alternate();
      $globalCount['deaths']=0;
      $globalCount['confirmed']=0;
      $globalCount['recovered']=0;
     if(is_array($allData)&& count($allData)>0){
      foreach($allData as $countrydata){
        $states = $countrydata["states"];
        $deaths= !empty($countrydata["total_deaths"])?$countrydata["total_deaths"]:0;
        $confirmed=!empty($countrydata["total_cases"])?$countrydata["total_cases"]:0;
       $recovered =!empty($countrydata["total_recovered"])?$countrydata["total_recovered"]:0;
       $test_million = !empty($countrydata["testsPerOneMillion"])?$countrydata["testsPerOneMillion"]:0;
     $globalCount['deaths']+=$deaths;
        $globalCount['confirmed']+=$confirmed;
        $s_code = cvct_interchange_us_states_name($states);
      $data_arr[]=array(
        "name"=> $states,
          "deaths"=>$deaths,
          "confirmed"=> $confirmed,
          "id"=>$s_code,
          "testsPerOneMillion"=>$test_million,
      );
   
      }
  }
  $countries_data['date']=date("Y-m-d"); 
  $countries_data['list']=$data_arr;  
  $globalCount['date']=date("Y-m-d"); 
  $map_data['covid_us_timeline']=$countries_data;
  $map_data['covid_us_total_timeline']=$globalCount;
  $map_json_data= json_encode($map_data); 
  $output = ''; 
  $output =   '<div data-title="'.$title.'" class="flexbox" id="cvct_wrapper">';
   $output.=  '<div id="cvct_map"></div>';
          if($show_list == 'yes') {
          $output.=  '<div id="list">
    <table class="cvct-basic-map-table" id="areas" class="compact hover order-column row-border">
      <thead>
        <tr>
          <th>Country/State</th>
          <th>Confirmed</th>
          <th>Deaths</th>
         </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>';
  }
$output.='</div><script type="application/json" id="cvct-map-data">'.$map_json_data.'</script>';
  $output.='<style>.flexbox {
    color:#fff;
    }</style>';
      return $output;
}
function cvct_map_load_assets(){
 
    wp_enqueue_script("cvct_core_js");
    wp_enqueue_script("cvct_amcharts_js");
    wp_enqueue_script("cvct_animated_js");
    wp_enqueue_script("cvct_maps",'https://www.amcharts.com/lib/4/maps.js',null,null,true);
    wp_enqueue_script("cvct_dark_theme",CVCT_URL.'assets/maps/dark.js',null,null,true);
   wp_enqueue_script("cvct_geodata",'https://www.amcharts.com/lib/4/geodata/usaLow.js',null,null,true);
    wp_enqueue_script("cvct_us_chart",CVCT_URL.'assets/js/cvct-us-stats.js',null,null,true);
    
    wp_enqueue_script("jquery_dataTables",CVCT_URL.'assets/maps/datatables/js/jquery.dataTables.min.js',array('jquery'),null,true);
    wp_enqueue_script("dataTables_select",CVCT_URL.'assets/maps/datatables/js/dataTables.select.min.js',array('jquery'),null,true);
    wp_enqueue_style("jquery_dataTables_css",CVCT_URL.'assets/maps/datatables/css/jquery.dataTables.min.css');
    wp_enqueue_style("select_dataTables",CVCT_URL.'assets/maps/datatables/css/select.dataTables.min.css');
    
    wp_enqueue_style("cvct_dark_theme",CVCT_URL.'assets/maps/dark.css');
    
    wp_enqueue_script('cvct_resizer_sensor');
    wp_enqueue_script('cvct_resizer_queries');
  }
}