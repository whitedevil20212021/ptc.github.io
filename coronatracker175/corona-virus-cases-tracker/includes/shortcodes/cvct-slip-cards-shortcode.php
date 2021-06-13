<?php
class CVCT_Slip_Card_Shortcode
{
function __construct() {
    //shortcodes for slip card 
   
    add_shortcode('cvct-slip-card',array($this,'cvct_slip_card'));
    
}



//slip card shortcode


function cvct_slip_card($atts,$content=null){
   
  
    wp_enqueue_script('cvct_slip_card');   

    $atts = shortcode_atts( array(        
        'country-code'=> 'IN',
        'title'=>'Covid19 Cases',
        'label-total'=>'Total Cases',
        'label-deaths'=>' Total Deaths',
        'label-recovered'=>'Total Recovered',
        'label-active'=>'Total Active',
        'label-global'=>"Global data",
        'bg-color'=>'#FCC25F',
        'font-color'=>'#000'
    ), $atts, 'cvct' );
    
    $country_code = !empty($atts['country-code'])?$atts['country-code']:'IN';
    $title = !empty($atts['title'])?$atts['title']:'Covid19 Cases';
    $label_total=!empty($atts['label-total'])?$atts['label-total']:'Total Cases';
    $label_deaths=!empty($atts['label-deaths'])?$atts['label-deaths']:'Total Deaths';
    $label_recovered=!empty($atts['label-recovered'])?$atts['label-recovered']:'Total Recovered';
    $label_active=!empty($atts['label-active'])?$atts['label-active']:'Total Active';    
    $label_global=!empty($atts['label-global'])?$atts['label-global']:"Global data";
    $bgColors=!empty($atts['bg-color'])?$atts['bg-color']:"#FCC25F";
    $fontColors=!empty($atts['font-color'])?$atts['font-color']:"#000";
    
        $get_data = cvct_country_stats_data($country_code);
        if($get_data == false){
            $get_data = cvct_country_stats_data_alternate($country_code);
        }  

        $g_data=cvct_get_global_data();
        if($g_data == false){
            $g_data=cvct_get_global_data_alternative();
        }    
      
        if($get_data==''){
         return false;
        }
        if($g_data==''){
         return false;
        }
   
    $alldata=isset($get_data['allData'])?$get_data['allData']:'';
    $flag_data=isset($alldata->countryInfo)?$alldata->countryInfo:'';
    $country = isset($alldata->country)?ucfirst($alldata->country):'';
    $flag=isset($flag_data->flag)?$flag_data->flag:'';
    $confirmed = isset($get_data['total_cases'])?(int) $get_data['total_cases']:'';
    $gconfirmed = isset($g_data['total_cases'])?(int) $g_data['total_cases']:'';
    $total_recover = isset($get_data['total_recovered'])?(int) $get_data['total_recovered']:'';
    $gtotal_recover = isset($g_data['total_recovered'])?(int) $g_data['total_recovered']:'';
    $total_death = isset($get_data['total_deaths'])?(int) $get_data['total_deaths']:'';
    $gtotal_death = isset($g_data['total_deaths'])?(int) $g_data['total_deaths']:'';
    $today_cases = isset($get_data['today_cases'])?(int) $get_data['today_cases']:'';
    $gtoday_cases = isset($g_data['today_cases'])?(int) $g_data['today_cases']:'';
    $today_deaths = isset($get_data['today_deaths'])?(int) $get_data['today_deaths']:'';
    $gtoday_deaths = isset($g_data['today_deaths'])?(int) $g_data['today_deaths']:'';   
    $total_active_cases=$confirmed-($total_recover+$total_death);
    $gtotal_active_cases=$gconfirmed-($gtotal_recover+$gtotal_death);
    $ap = ($total_active_cases/$confirmed)*100;
    $gap = ($gtotal_active_cases/$gconfirmed)*100;
    $rp = ($total_recover/$confirmed)*100;
    $grp = ($gtotal_recover/$gconfirmed)*100;
    $dp = ($total_death/$confirmed)*100;
    $activePercentage=!empty($ap)? number_format($ap,1)."%":"N/A";
    $gactivePercentage=!empty($gap)? number_format($gap,1)."%":"N/A";
    $recoverdPerctange = !empty($rp)?number_format($rp,1)."%":"N/A";
    $grecoverdPerctange = !empty($grp)?number_format($grp,1)."%":"N/A";
    $deathPerctange = !empty($dp)?number_format($dp,1)."%":"N/A";

    $sl_html='';
     
    $sl_html.='
    
    <div id="slip_tab" >

        <span id=s_title> '.esc_html($title).'</span>

	    <ul class="tabs">
	    	<li class="tab-link current" data-tab="tab-1"><img src="'. $flag.'"  height="30" width="30">  '.esc_html($country).'</li>
	    	<li class="tab-link" data-tab="tab-2"><img src="'.CVCT_URL.'/assets/images/cvct-world.png"  height="25" width="25"> '.esc_html($label_global).'</li>
	
	    </ul>

	 <div id="tab-1" class="tab-content current ">
        <div class="cvct_row" >
            <div class="cvct_mview">
               <p class="cvct_confirmed">+'.esc_html($today_cases).'(24H)</p>
               <span class="cvct_larg-no">'.esc_html(($confirmed==0)?'N/A':number_format($confirmed)).'</span>
               <p class="cvct-lbl">'.esc_html($label_total).'</p>
 
            </div>
            <div class="cvct-vl">
               <p class="cvct_deaths">+'.esc_html($today_deaths).'(24H)</p>
               <span class="cvct_larg-no">'.esc_html(($total_death==0)?'N/A':number_format($total_death)).'</span>
               <P class="cvct-lbl">'.esc_html($label_deaths).'</P>

            </div>
            <div class="cvct-vl" >
               <p class="cvct_recovered">'.esc_html($recoverdPerctange).'</p>
               <span class="cvct_larg-no">'.esc_html(($total_recover==0)?'N/A':number_format($total_recover)).'</span>
               <P class="cvct-lbl">'.esc_html($label_recovered).'</P>
            </div>
            <div class="cvct-vl" >
               <p class="cvct_active">'.esc_html($activePercentage).'</p>
               <span class="cvct_larg-no">'.esc_html(($total_active_cases==0)?'N/A':number_format($total_active_cases)).'</span>
               <P class="cvct-lbl">'.esc_html($label_active).'</P>
            </div>
        </div>    
     </div>
	
     <div id="tab-2" class="tab-content ">
   
      <div class="cvct_row" >
         <div class="cvct_mview">
             <p class="cvct_confirmed">+'.esc_html($gtoday_cases).'(24H)</p>
             <span class="cvct_larg-no">'.esc_html(number_format($gconfirmed)).'</span>
             <p class="cvct-lbl">'.esc_html($label_total).'</p>

         </div>
         <div class="cvct-vl"  >
            <p class="cvct_deaths">+'.esc_html($gtoday_deaths).'(24H)</p>
            <span class="cvct_larg-no">'.esc_html(number_format($gtotal_death)).'</span>
            <span class="cvct-lbl">'.esc_html($label_deaths).'</span>

         </div>
         <div class="cvct-vl" >
               <p class="cvct_recovered">'.esc_html($grecoverdPerctange).'</p>
               <span class="cvct_larg-no">'.esc_html(number_format($gtotal_recover)).'</span>
               <span class="cvct-lbl">'.esc_html($label_recovered).'</span>
         </div>
         <div class="cvct-vl" >
            <p class="cvct_active">'.esc_html($gactivePercentage).'</p>
            <span class="cvct_larg-no">'.esc_html(number_format($gtotal_active_cases)).'</span>
            <span class="cvct-lbl">'.esc_html($label_active).'</span>
         </div>
      </div>
     </div>     
 </div>
   
    ';
   
$sl_html.='<style>
   
    #s_title{
        font-size: 50px;
        font-weight: bold;
        color:'.$fontColors.';
    }
    .cvct-lbl{
        font-weight: bold;
        color:'.$fontColors.';
    }
    .cvct_row{
        margin-left: 5px;
        display: inline;
        margin-top:10px;
    }
    .cvct_larg-no{

        font-size:30px;
        font-weight: bold;
        color:'.$fontColors.';

    }
    .cvct_confirmed{
        color:#68371A;
        font-weight: bold;
        margin-bottom: 0px;
        
    }
    .cvct_deaths{
        color:#C61414;
        font-weight: bold;
        margin-bottom: 0px;
    }
    .cvct_recovered{
        color:#008000; 
        font-weight: bold;
        margin-bottom: 0px;

    }
    .cvct_active{
        color:#5112E4;
        font-weight: bold;
        margin-bottom: 0px;
    }
    .cvct-vl {
        border-left: 1px solid #695950;
        width:170px;
        padding-left: 30px;    
        display: inline-block;
       
      }
      .cvct_mview{
        display: inline-block;

     }
    #slip_tab{
        display: inline-block;
        width: 100%;
        max-width: 750px;
        border: 1px solid rgba(0, 0, 0, 0.14);
        padding: 10px;
        border-radius: 8px;
        background: '.$bgColors.' url('.CVCT_URL.'assets/corona-virus.png);
        background-size: 100px;
        background-position: right -20px top -18px;
        background-repeat: no-repeat;
        transition: background-position 1s;
        height: auto;    
    }

    #slip_tab:hover {
        background-position: right -7px top -5px;
        transition: background-position 1s;        
    }
    ul.tabs{
        margin: 0px;
        padding: 0px;
        list-style: none;
        margin-bottom:10px;
    }
    ul.tabs li{
       
        color: '.$fontColors.';
        display: inline-block;
        padding: 5px 10px;
        cursor: pointer;
        font-weight: bold;
    }

    ul.tabs li.current{
     
        background-color: #F5DEB3;
        color: '.$fontColors.';
        border-radius: 8px;
        opacity: 0.6;
        font-weight: bold;
        
        
    }

    .tab-content{
        display: none;
       
        padding: 15px;
    }

    .tab-content.current{
        display: inline;
    }

    @media (max-width: 500px) {

     
         .tab-content{
            display: none;
           
            padding: 15px;
        }
        
        .tab-content.current{
            display: inline-block;
            margin-left: -20px;
            
            
        }

        ul.tabs li{
       
            color: '.$fontColors.';
            display: inline-block;
            padding: 2px 5px;
            cursor: pointer;
            font-weight: bold;
        }
    
        ul.tabs li.current{
            background: #ffff;
            color: '.$fontColors.';
            border-radius: 5px;
            opacity: 0.6;
            font-weight: bold;
        }

         .cvct-vl {
            border-left: 0px ;
            width:80px;
            margin-left: -12px;
            

         }
         .cvct_mview{
            border-left: 0px;
            width:80px;
            margin-right: -20px;
           

         }
         .cvct_larg-no{

            font-size:15px;
            font-weight: bold;
            color:'.$fontColors.';
    
        }
        #s_title{
            font-size: 30px;
            font-weight: bold;
            color:'.$fontColors.';
        }   
    }
  
  
  </style>';
    return $sl_html;
    
}


}


