<input type="hidden" id="stats_url" value="<?php echo site_url('stats/index'); ?>">
<input type="hidden" id="start_time" value="<?php echo $start_time; ?>">
<input type="hidden" id="end_time" value="<?php echo $end_time; ?>">

<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Social Settings
                    <small></small>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>  
        <div class="row-fluid" style='margin-bottom: 20px;'>
            <div class="portlet box yellow">
                <div class="portlet-title" style="border-bottom: none;">
                    <h4>&nbsp;</h4>
                    <div class="pull-right alltime-holder" style="display: none;">
                        <a class="btn green mini" name="alltime" id="alltime" href="#">All time</a> 
                    </div>
                    <div class="pull-right calendar-holder" style="cursor: pointer;">
                        <div id="dashboard-report-range" class="dashboard-date-range tooltips no-tooltip-on-touch-device responsive" data-tablet="" data-desktop="tooltips" data-placement="top" data-original-title="Change date range">
                            <i class="icon-calendar"></i>
                            <span></span>
                            <i class="icon-angle-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>  
        <div class="row-fluid">
            <div class="span6">
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Post by Date</h4>
                    </div>
                    <div class="portlet-body">
                        <div id="site_activities_loading">
                            <!--<img src="http://127.0.0.1/premier_jump//assets/img/loading.gif" alt="loading" />-->
                        </div>
                        <div lass="hide">
                            <div id="stats_post"  class="chart" style="height:300px;"></div>
                        </div>
                    </div>
                </div>
                                
            </div>
            <div class="span6">
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Gender</h4>
                    </div>
                    <div class="portlet-body">
                        <div id="stats_gender" class="chart"></div>
                    </div>
                </div>
            </div>
                <!-- BEGIN PORTLET-->
            
        </div>
        <div class="row-fluid">
            <div class="span6">
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Age</h4>
                    </div>
                    <div class="portlet-body">
                        <div id="stats_age" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="portlet solid bordered light-grey">
                    <div class="portlet-title">
                        <h4><i class="icon-bar-chart"></i>Credit Purchased</h4>
                    </div>
                    <div class="portlet-body">
                        <div id="site_statistics_loading">
                            <img src="assets/img/loading.gif" alt="loading" />
                        </div>
                        <div id="site_statistics_content" class="hide">
                            <div id="site_statistics" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- BEGIN PORTLET-->
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function() { 
        $start_time = $('#start_time').val();
        $end_time = $('#end_time').val();
        if(!$start_time){
            $start_time = Date.today().add({days: -29}).getTime()/1000;
            $end_time = Date.today().add({days: 1}).getTime()/1000;
        }

/**
*         
*/
        $('#site_activities_loading').hide();
        $('#site_activities_content').show();
        function plotWithOptions(y_data, x_data, container_id) {
            var stack = 0, bars = true, lines = false, steps = false;
        
            $.plot($("#"+container_id), [y_data], {
                series: {
                    stack: stack,
                    lines: {
                        show: lines,
                        fill: true,
                        steps: steps
                    },
                    bars: {
                        show: true,
                        align: "center",
                        barWidth: 0.4,
                        fillColor: {colors: [{ opacity: 0.8 }, { opacity: 0.1 }, { opacity: 0.5 }]}
                    }
                },
                xaxis: {
                    ticks: x_data,
                }
                
            });
        }
        call_api("getStatsPost", {'start_time':$start_time, 'end_time':$end_time}, function($data){
            var y_data = [];
            var x_data = [];
            for(var i=0; i<$data.length; i++){
                y_data.push([i, $data[i].stat_cnt]);
                x_data.push([i, $data[i].stat_date]);
            }
            plotWithOptions(y_data, x_data, 'stats_post');
        });

/**********
*         
*/
        function plotStatsGender(statsData, container_id){
            $.plot($("#"+container_id), statsData, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: function (label, series) {
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                            },
                            background: {
                                opacity: 0.5
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        }
        call_api("getStatsGender", {'start_time':$start_time, 'end_time':$end_time}, function($data){
            var statsGenderData = [];
            for(var i=0; i<$data.length; i++){
                statsGenderData[i] = {label:$data[i].stat_label, data:Number($data[i].stat_cnt)};
            }
            plotStatsGender(statsGenderData, 'stats_gender');
        });
        call_api("getStatsAge", {'start_time':$start_time, 'end_time':$end_time}, function($data){
            var statsAgeData = [];
            for(var i=0; i<$data.length; i++){
                statsAgeData[i] = {label:$data[i].stat_label, data:Number($data[i].stat_cnt)};
            }
            console.log(statsAgeData)
            plotStatsGender(statsAgeData, 'stats_age');
        });
       

        
        function randValue() {
            return (Math.floor(Math.random() * (1 + 50 - 20))) + 10;
        }
        var statsData1 = [
            [1, randValue()],
            [2, randValue()],
            [3, 2 + randValue()],
            [4, 3 + randValue()],
            [5, 5 + randValue()],
            [6, 10 + randValue()],
        ];
        function plotStatsPurchase(yData, xData, container_id){
            $('#site_statistics_loading').hide();
            $('#site_statistics_content').show();
            $.plot($("#"+container_id), [{
                data: yData,
                label: "Purchased"
            }], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.05
                            }, {
                                opacity: 0.01
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#eee",
                    borderWidth: 0
                },
                colors: ["#d12610", "#37b7f3", "#52e136"],
                xaxis: {
                    ticks: xData
                },
                yaxis: {
                    ticks: 11,
                    tickDecimals: 0
                }
            });
        }
        call_api("getStatsPurchase", {'start_time':$start_time, 'end_time':$end_time}, function($data){
            var yData = [];
            var xData = [];
            for(var i=0; i<$data.length; i++){
                yData[i] = [i, $data[i].stat_value];
                xData[i] = [i, $data[i].stat_label];
            }
            plotStatsPurchase(yData, xData, 'site_statistics');
        });
    });
    
</script>
