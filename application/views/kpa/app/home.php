<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<ul class="page-breadcrumb breadcrumb">
    <li>
        <a href="index.html">Home</a>
        <i class="fa fa-circle"></i>
    </li>
    <li>
        <span>Dashboard</span>
    </li>
</ul>
<?php
$bulan_lalu = 0;
$where_bulan = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', '', "a.bulan DESC", '1')->row();
?>
<div class="page-content-inner">
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="portlet light ">
                <!-- <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark uppercase bold">Sales Summary</span>
                        <span class="caption-helper hide">weekly stats...</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Today</label>
                            <label class="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Week</label>
                            <label class="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Month</label>
                        </div>
                    </div>
                </div> -->
                <div class="portlet-body">
                    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> -->
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <!-- <p class="highcharts-description">
                            Chart showing overlapping placement of columns, using different data
                            series. The chart is also using multiple y-axes, allowing data in
                            different ranges to be visualized on the same chart.
                        </p> -->
                    </figure>
                    <script>
                        Highcharts.chart('container', {
                            chart: {
                                type: 'column'
                            },
                            credits: {
                                enabled: false
                            },
                            title: {
                                text: 'Rekap Anggaran Data Kegiatan'
                            },
                            xAxis: {
                                categories: [
                                    <?php
                                    $data_kegiatan = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*,(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_kegiatan=a.kode_kegiatan) as realisasi')->result();
                                    foreach ($data_kegiatan as $key => $value) {
                                        echo"'".$value->kegiatan."',";
                                    }
                                    ?>
                                ]
                            },
                            yAxis: [{
                                min: 0,
                                title: {
                                    text: 'Juta Rupiah'
                                }
                            }],
                            legend: {
                                shadow: false
                            },
                            tooltip: {
                                shared: true
                            },
                            plotOptions: {
                                column: {
                                    grouping: false,
                                    shadow: false,
                                    borderWidth: 0
                                }
                            },
                            series: [{
                                name: 'Pagu',
                                color: 'rgba(165,170,217,1)',
                                data: [
                                <?php
                                $data_kegiatan = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*,(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_kegiatan=a.kode_kegiatan) as realisasi')->result();
                                foreach ($data_kegiatan as $key => $value) {
                                    echo $value->pagu.",";
                                }
                                ?>
                                ],
                                tooltip: {
                                    valuePrefix: 'Rp ',
                                    // valueSuffix: ' M'
                                },
                                pointPadding: 0.3,
                            }, {
                                name: 'Realisasi',
                                color: 'rgba(126,86,134,.9)',
                                data: [
                                <?php
                                $data_kegiatan = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*,(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_kegiatan=a.kode_kegiatan AND b.bulan="'.$this->Main_model->get_where_bulan().'") as realisasi')->result();
                                foreach ($data_kegiatan as $key => $value) {
                                    echo $value->realisasi.",";
                                }
                                ?>
                                ],
                                tooltip: {
                                    valuePrefix: 'Rp ',
                                    // valueSuffix: ' M'
                                },
                                pointPadding: 0.4,
                            }]
                        });
                    </script>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="portlet light ">
                <!-- <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Member Activity</span>
                        <span class="caption-helper">weekly stats...</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn btn-transparent green btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Today</label>
                            <label class="btn btn-transparent green btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Week</label>
                            <label class="btn btn-transparent green btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Month</label>
                        </div>
                    </div>
                </div> -->
                <div class="portlet-body">
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                    <figure class="highcharts-figure">
                        <div id="container2"></div>
                        <!-- <p class="highcharts-description">
                            Pie charts are very popular for showing a compact overview of a
                            composition or comparison. While they can be harder to read than
                            column charts, they remain a popular choice for small datasets.
                        </p> -->
                    </figure>
                    <script>
                        // Highcharts.createElement('link', {
                        //     href: 'https://fonts.googleapis.com/css?family=Unica+One',
                        //     rel: 'stylesheet',
                        //     type: 'text/css'
                        // }, null, document.getElementsByTagName('head')[0]);
                        // Highcharts.theme = {
                        //     colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066',
                        //         '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
                        //     chart: {
                        //         backgroundColor: {
                        //             linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                        //             stops: [
                        //                 [0, '#2a2a2b'],
                        //                 [1, '#3e3e40']
                        //             ]
                        //         },
                        //         style: {
                        //             fontFamily: '\'Unica One\', sans-serif'
                        //         },
                        //         plotBorderColor: '#606063'
                        //     },
                        //     title: {
                        //         style: {
                        //             color: '#E0E0E3',
                        //             textTransform: 'uppercase',
                        //             fontSize: '20px'
                        //         }
                        //     },
                        //     subtitle: {
                        //         style: {
                        //             color: '#E0E0E3',
                        //             textTransform: 'uppercase'
                        //         }
                        //     },
                        //     xAxis: {
                        //         gridLineColor: '#707073',
                        //         labels: {
                        //             style: {
                        //                 color: '#E0E0E3'
                        //             }
                        //         },
                        //         lineColor: '#707073',
                        //         minorGridLineColor: '#505053',
                        //         tickColor: '#707073',
                        //         title: {
                        //             style: {
                        //                 color: '#A0A0A3'
                        //             }
                        //         }
                        //     },
                        //     yAxis: {
                        //         gridLineColor: '#707073',
                        //         labels: {
                        //             style: {
                        //                 color: '#E0E0E3'
                        //             }
                        //         },
                        //         lineColor: '#707073',
                        //         minorGridLineColor: '#505053',
                        //         tickColor: '#707073',
                        //         tickWidth: 1,
                        //         title: {
                        //             style: {
                        //                 color: '#A0A0A3'
                        //             }
                        //         }
                        //     },
                        //     tooltip: {
                        //         backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        //         style: {
                        //             color: '#F0F0F0'
                        //         }
                        //     },
                        //     plotOptions: {
                        //         series: {
                        //             dataLabels: {
                        //                 color: '#F0F0F3',
                        //                 style: {
                        //                     fontSize: '13px'
                        //                 }
                        //             },
                        //             marker: {
                        //                 lineColor: '#333'
                        //             }
                        //         },
                        //         boxplot: {
                        //             fillColor: '#505053'
                        //         },
                        //         candlestick: {
                        //             lineColor: 'white'
                        //         },
                        //         errorbar: {
                        //             color: 'white'
                        //         }
                        //     },
                        //     legend: {
                        //         backgroundColor: 'rgba(0, 0, 0, 0.5)',
                        //         itemStyle: {
                        //             color: '#E0E0E3'
                        //         },
                        //         itemHoverStyle: {
                        //             color: '#FFF'
                        //         },
                        //         itemHiddenStyle: {
                        //             color: '#606063'
                        //         },
                        //         title: {
                        //             style: {
                        //                 color: '#C0C0C0'
                        //             }
                        //         }
                        //     },
                        //     credits: {
                        //         style: {
                        //             color: '#666'
                        //         }
                        //     },
                        //     labels: {
                        //         style: {
                        //             color: '#707073'
                        //         }
                        //     },
                        //     drilldown: {
                        //         activeAxisLabelStyle: {
                        //             color: '#F0F0F3'
                        //         },
                        //         activeDataLabelStyle: {
                        //             color: '#F0F0F3'
                        //         }
                        //     },
                        //     navigation: {
                        //         buttonOptions: {
                        //             symbolStroke: '#DDDDDD',
                        //             theme: {
                        //                 fill: '#505053'
                        //             }
                        //         }
                        //     },
                        //     // scroll charts
                        //     rangeSelector: {
                        //         buttonTheme: {
                        //             fill: '#505053',
                        //             stroke: '#000000',
                        //             style: {
                        //                 color: '#CCC'
                        //             },
                        //             states: {
                        //                 hover: {
                        //                     fill: '#707073',
                        //                     stroke: '#000000',
                        //                     style: {
                        //                         color: 'white'
                        //                     }
                        //                 },
                        //                 select: {
                        //                     fill: '#000003',
                        //                     stroke: '#000000',
                        //                     style: {
                        //                         color: 'white'
                        //                     }
                        //                 }
                        //             }
                        //         },
                        //         inputBoxBorderColor: '#505053',
                        //         inputStyle: {
                        //             backgroundColor: '#333',
                        //             color: 'silver'
                        //         },
                        //         labelStyle: {
                        //             color: 'silver'
                        //         }
                        //     },
                        //     navigator: {
                        //         handles: {
                        //             backgroundColor: '#666',
                        //             borderColor: '#AAA'
                        //         },
                        //         outlineColor: '#CCC',
                        //         maskFill: 'rgba(255,255,255,0.1)',
                        //         series: {
                        //             color: '#7798BF',
                        //             lineColor: '#A6C7ED'
                        //         },
                        //         xAxis: {
                        //             gridLineColor: '#505053'
                        //         }
                        //     },
                        //     scrollbar: {
                        //         barBackgroundColor: '#808083',
                        //         barBorderColor: '#808083',
                        //         buttonArrowColor: '#CCC',
                        //         buttonBackgroundColor: '#606063',
                        //         buttonBorderColor: '#606063',
                        //         rifleColor: '#FFF',
                        //         trackBackgroundColor: '#404043',
                        //         trackBorderColor: '#404043'
                        //     }
                        // };
                        // Highcharts.setOptions(Highcharts.theme);
                        Highcharts.chart('container2', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            credits: {
                                enabled: false
                            },
                            title: {
                                text: 'Sebaran Penggunaan Anggaran Tahun 2020'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            accessibility: {
                                point: {
                                    valueSuffix: '%'
                                }
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                    }
                                }
                            },
                            series: [{
                                name: 'Serapan',
                                colorByPoint: true,
                                data: [
                                // {
                                //     name: 'Kegiatan A',
                                //     y: 61.41,
                                //     sliced: true,
                                //     selected: true
                                // }, {
                                //     name: 'Kegiatan B',
                                //     y: 11.84
                                // }, {
                                //     name: 'Kegiatan C',
                                //     y: 10.85
                                // }, {
                                //     name: 'Kegiatan D',
                                //     y: 4.67
                                // }, {
                                //     name: 'Kegiatan E',
                                //     y: 4.18
                                // }, {
                                //     name: 'Kegiatan F',
                                //     y: 1.64
                                // }, {
                                //     name: 'Kegiatan G',
                                //     y: 1.6
                                // }, {
                                //     name: 'Kegiatan H',
                                //     y: 1.2
                                // }, {
                                //     name: 'Kegiatan I',
                                //     y: 2.61
                                // }
                                <?php
                                $data_departemen = $this->Main_model->getSelectedData('departemen a', 'a.*')->result();
                                foreach ($data_departemen as $key => $value) {
                                    $data_kegiatan = $this->Main_model->getSelectedData('kegiatan a', '(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_sub_komponen=a.kode_kegiatan AND b.bulan="'.$this->Main_model->get_where_bulan().'") AS jum', array('a.id_departemen'=>$value->id_departemen))->result();
                                    $jum = 0;
                                    foreach ($data_kegiatan as $key => $row) {
                                        $jum += $row->jum;
                                    }
                                    echo"
                                    {
                                        name: '".$value->kode_departemen."',
                                        y: ".$jum."
                                    },
                                    ";
                                }
                                ?>
                                ]
                            }]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark bold">Rekap Penyerapan Anggaran Per Bulan Tahun 2020</span>
                        <!-- <span class="caption-helper">45 pending</span> -->
                    </div>
                    <!-- <div class="inputs">
                        <div class="portlet-input input-inline input-small ">
                            <div class="input-icon right">
                                <i class="icon-magnifier"></i>
                                <input type="text" class="form-control form-control-solid input-circle" placeholder="search..."> </div>
                        </div>
                    </div> -->
                </div>
                <div class="portlet-body">
                    <style>
                        #chartdiv {
                            width: 100%;
                            height: 500px;
                        }
                    </style>

                    <!-- Resources -->
                    <script src="https://www.amcharts.com/lib/4/core.js"></script>
                    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
                    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

                    <!-- Chart code -->
                    <script>
                        am4core.ready(function() {

                        // Themes begin
                        am4core.useTheme(am4themes_animated);
                        // Themes end

                        // Create chart instance
                        var chart = am4core.create("chartdiv", am4charts.XYChart);
                        chart.scrollbarX = new am4core.Scrollbar();

                        // Add data
                        chart.data = [
                        {
                        "country": "Januari",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-01'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum;
                        $bulan_lalu = $jum;
                        ?>
                        }, {
                        "country": "Februari",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-02'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        $bulan_lalu = $jum;
                        ?>
                        }, {
                        "country": "Maret",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-03'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }
                        ];

                        // Create axes
                        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "country";
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 30;
                        categoryAxis.renderer.labels.template.horizontalCenter = "right";
                        categoryAxis.renderer.labels.template.verticalCenter = "middle";
                        categoryAxis.renderer.labels.template.rotation = 270;
                        categoryAxis.tooltip.disabled = true;
                        categoryAxis.renderer.minHeight = 110;

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        valueAxis.renderer.minWidth = 50;

                        // Create series
                        var series = chart.series.push(new am4charts.ColumnSeries());
                        series.sequencedInterpolation = true;
                        series.dataFields.valueY = "visits";
                        series.dataFields.categoryX = "country";
                        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                        series.columns.template.strokeWidth = 0;

                        series.tooltip.pointerOrientation = "vertical";

                        series.columns.template.column.cornerRadiusTopLeft = 10;
                        series.columns.template.column.cornerRadiusTopRight = 10;
                        series.columns.template.column.fillOpacity = 0.8;

                        // on hover, make corner radiuses bigger
                        var hoverState = series.columns.template.column.states.create("hover");
                        hoverState.properties.cornerRadiusTopLeft = 0;
                        hoverState.properties.cornerRadiusTopRight = 0;
                        hoverState.properties.fillOpacity = 1;

                        series.columns.template.adapter.add("fill", function(fill, target) {
                        return chart.colors.getIndex(target.dataItem.index);
                        });

                        // Cursor
                        chart.cursor = new am4charts.XYCursor();

                        }); // end am4core.ready()
                    </script>

                    <!-- HTML -->
                    <div id="chartdiv"></div>		
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark bold">RKAKL</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php
                    $get_file = $this->Main_model->getSelectedData('rkakl a', 'a.*',array('a.is_active'=>'1'))->row();
                    ?>
                    <iframe height="600" width="100%" src="<?=base_url()?>data_upload/rkakl/<?= $get_file->file; ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT INNER -->