<?php
class CVCT_Cases_overview_Card_Shortcode
{
    public function __construct()
    {
        //shortcodes for overview card

        add_shortcode('cvct-cases-overview-card', array($this, 'cvct_cases_overview_card'));

    }

    //overview card shortcode

    public function cvct_cases_overview_card($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'country-code' => 'IN',
            'show-world-data' => 'yes',
            'title' => 'Covid19 Cases Overview',
            'label-total' => 'Confirmed',
            'label-deaths' => ' Deaths',
            'label-recovered' => 'Recovered',
            'label-active' => 'Active',
            'label-global' => "Worldwide",
            'bg-color' => '#D5B5B5',
            'font-color' => '#000',
        ), $atts, 'cvct');

        $country_code = !empty($atts['country-code']) ? $atts['country-code'] : 'IN';
        $world_data = !empty($atts['show-world-data']) ? $atts['show-world-data'] : 'yes';
        $title = !empty($atts['title']) ? $atts['title'] : 'Covid19 Cases Overview';
        $label_total = !empty($atts['label-total']) ? $atts['label-total'] : 'Confirmed';
        $label_deaths = !empty($atts['label-deaths']) ? $atts['label-deaths'] : 'Deaths';
        $label_recovered = !empty($atts['label-recovered']) ? $atts['label-recovered'] : 'Recovered';
        $label_active = !empty($atts['label-active']) ? $atts['label-active'] : 'Active';
        $label_global = !empty($atts['label-global']) ? $atts['label-global'] : "Worldwide";
        $bgColors = !empty($atts['bg-color']) ? $atts['bg-color'] : "#FCC25F";
        $fontColors = !empty($atts['font-color']) ? $atts['font-color'] : "#000";
        $get_data = cvct_country_stats_data($country_code);
        if ($get_data == false) {
            $get_data = cvct_country_stats_data_alternate($country_code);
        }

        $g_data = cvct_get_global_data();
        if ($g_data == false) {
            $g_data = cvct_get_global_data_alternative();
        }

        if ($get_data == '') {
            return false;
        }
        if ($g_data == '') {
            return false;
        }

        $alldata = isset($get_data['allData']) ? $get_data['allData'] : '';
        $flag_data = isset($alldata->countryInfo) ? $alldata->countryInfo : '';
        $country = isset($alldata->country) ? ucfirst($alldata->country) : '';
        $flag = isset($flag_data->flag) ? $flag_data->flag : '';
        $confirmed = isset($get_data['total_cases']) ? (int) $get_data['total_cases'] : '';
        $gconfirmed = isset($g_data['total_cases']) ? (int) $g_data['total_cases'] : '';
        $total_recover = isset($get_data['total_recovered']) ? (int) $get_data['total_recovered'] : '';
        $gtotal_recover = isset($g_data['total_recovered']) ? (int) $g_data['total_recovered'] : '';
        $total_death = isset($get_data['total_deaths']) ? (int) $get_data['total_deaths'] : '';
        $gtotal_death = isset($g_data['total_deaths']) ? (int) $g_data['total_deaths'] : '';
        $today_cases = isset($get_data['today_cases']) ? (int) $get_data['today_cases'] : '';
        $gtoday_cases = isset($g_data['today_cases']) ? (int) $g_data['today_cases'] : '';
        $today_deaths = isset($get_data['today_deaths']) ? (int) $get_data['today_deaths'] : '';
        $gtoday_deaths = isset($g_data['today_deaths']) ? (int) $g_data['today_deaths'] : '';
        $total_active_cases = $confirmed - ($total_recover + $total_death);
        $gtotal_active_cases = $gconfirmed - ($gtotal_recover + $gtotal_death);
        $ap = ($total_active_cases / $confirmed) * 100;
        $gap = ($gtotal_active_cases / $gconfirmed) * 100;
        $rp = ($total_recover / $confirmed) * 100;
        $grp = ($gtotal_recover / $gconfirmed) * 100;
        $dp = ($total_death / $confirmed) * 100;
        $activePercentage = !empty($ap) ? number_format($ap, 1) . "%" : "N/A";
        $gactivePercentage = !empty($gap) ? number_format($gap, 1) . "%" : "N/A";
        $recoverdPerctange = !empty($rp) ? number_format($rp, 1) . "%" : "N/A";
        $grecoverdPerctange = !empty($grp) ? number_format($grp, 1) . "%" : "N/A";
        $deathPerctange = !empty($dp) ? number_format($dp, 1) . "%" : "N/A";

        wp_enqueue_script('cvct_jquery_dt');
        wp_add_inline_script('cvct_jquery_dt', '

        jQuery(document).ready(function($){
            var data=$("#s_title").attr("gdata");
            if(data=="yes"){
                 $("#world-dt").show();
            }
            else{
                $("#world-dt").hide();
            }

        });'

        );

        $sl_html = '';

        $sl_html .= '

    <div id="slip_tab" >
        <span id="s_title" gdata=' . $world_data . '> ' . esc_html($title) . '</span>
    <div  class="tab-content">
    <li ><img src="' . $flag . '"  height="30" width="30">  ' . esc_html($country) . '</li>
    <div class="cvct_row" >
        <div class="cvct_mview">
        <span class="cvct-lbl">' . esc_html($label_total) . '</span>
        <span class="cvct_larg-no">' . esc_html(($confirmed == 0) ? 'N/A' : number_format($confirmed)) . '</span>
        <p class="cvct_confirmed">+' . esc_html($today_cases) . '</p>
        </div>
        <div class="cvct-vl">
            <span class="cvct-lbl">' . esc_html($label_deaths) . '</span>
            <span class="cvct_larg-no">' . esc_html(($total_death == 0) ? 'N/A' : number_format($total_death)) . '</span>
            <p class="cvct_deaths">+' . esc_html($today_deaths) . '</p>
        </div>
        <div class="cvct-vl" >
            <span class="cvct-lbl">' . esc_html($label_recovered) . '</span>
            <span class="cvct_larg-no">' . esc_html(($total_recover == 0) ? 'N/A' : number_format($total_recover)) . '</span>
            <p class="cvct_recovered">' . esc_html($recoverdPerctange) . '</p>
        </div>
        <div class="cvct-vl" >
            <span class="cvct-lbl">' . esc_html($label_active) . '</span>
            <span class="cvct_larg-no">' . esc_html(($total_active_cases == 0) ? 'N/A' : number_format($total_active_cases)) . '</span>
            <p class="cvct_active">' . esc_html($activePercentage) . '</p>
        </div>
    </div>
    </div>

     <div class="tab-content" id="world-dt">
     <li ><img src="' . CVCT_URL . '/assets/images/cvct-world.png"  height="25" width="25"> ' . esc_html($label_global) . '</li>

      <div class="cvct_row" >
        <div class="cvct_mview">

            <span class="cvct-lbl">' . esc_html($label_total) . '</span>
            <span class="cvct_larg-no">' . esc_html($this->cvct_format_number($gconfirmed)) . '</span>
            <p class="cvct_confirmed">+' . esc_html($gtoday_cases) . '</p>

        </div>
         <div class="cvct-vl"  >

            <span class="cvct-lbl">' . esc_html($label_deaths) . '</span>
            <span class="cvct_larg-no">' . esc_html($this->cvct_format_number($gtotal_death)) . '</span>
            <p class="cvct_deaths">+' . esc_html($gtoday_deaths) . '</p>

         </div>
         <div class="cvct-vl" >
               <span class="cvct-lbl">' . esc_html($label_recovered) . '</span>
               <span class="cvct_larg-no">' . esc_html($this->cvct_format_number($gtotal_recover)) . '</span>
               <p class="cvct_recovered">' . esc_html($grecoverdPerctange) . '</p>
         </div>
         <div class="cvct-vl" >

            <span class="cvct-lbl">' . esc_html($label_active) . '</span>
            <span class="cvct_larg-no">' . esc_html($this->cvct_format_number($gtotal_active_cases)) . '</span>
            <p class="cvct_active">' . esc_html($gactivePercentage) . '</p>
         </div>
      </div>
     </div>
 </div>

    ';

        $sl_html .= '<style>

    #s_title{
        font-size: 25px;
        font-weight: bold;
        color:' . $fontColors . ';


    }
    .cvct-lbl{

        color:' . $fontColors . ';
        font-weight: bold;
        display:block;

    }
    .cvct_row{
        margin-left: 20px;
        display: inline-block;


    }
    .cvct_larg-no{

        font-size:20px;
        font-weight: bold;
        color:' . $fontColors . ';

    }
    .cvct_confirmed{
        color:#68371A;
        font-weight: bold;


    }
    .cvct_deaths{
        color:#C61414;
        font-weight: bold;

    }
    .cvct_recovered{
        color:#008000;
        font-weight: bold;


    }
    .cvct_active{
        color:#5112E4;
        font-weight: bold;

    }
    .cvct-vl {
        border-left: 1px solid #5B5954;
        width:170px;
        padding-left: 30px;
        display: inline-block;

      }
      .cvct_mview{
        display: inline-block;

        width:120px;

     }
    #slip_tab{
        display: block;
        width: 100%;
        max-width: 750px;
        border: 1px solid rgba(0, 0, 0, 0.14);
        padding: 10px;
        border-radius: 8px;
        background: ' . $bgColors . ';

        height: auto;
    }

     li{

        color: ' . $fontColors . ';
        display: block;

        margin-top:10px;
        font-weight: bold;
        margin-bottom:12px;
    }

    .tab-content{
        display: inline-block;
        margin-bottom:15px;

         border-top: 1px solid #5B5954;

    }



    @media (max-width: 500px) {


         .tab-content{
            display: inline-block;


        }

          li{

            color: ' . $fontColors . ';
             display: block;
              cursor: pointer;
              font-weight: bold;
              margin-bottom:8px;
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
            color:' . $fontColors . ';

        }
        #s_title{
            font-size: 20px;
            font-weight: bold;
            color:' . $fontColors . ';
        }
    }


  </style>';
        return $sl_html;

    }

    public function cvct_format_number($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        // now filter it;
        if ($n > 1000000000000) {
            return round(($n / 1000000000000), 2) . 'T';
        } else if ($n > 1000000000) {
            return round(($n / 1000000000), 2) . 'B';
        } else if ($n > 1000000) {
            return round(($n / 1000000), 2) . 'M';
        } else if ($n > 1000) {
            return round(($n / 1000), 2) . 'K';
        }

        return number_format($n);
    }

}
