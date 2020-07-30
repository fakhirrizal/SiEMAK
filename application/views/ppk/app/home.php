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
?>
<!-- END PAGE BREADCRUMBS -->
<!-- BEGIN PAGE CONTENT INNER -->
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
                                    $data_kegiatan = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*,(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_kegiatan=a.kode_kegiatan AND b.bulan="'.$this->Main_model->get_where_bulan().'") as realisasi')->result();
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
                                $data_kegiatan = $this->Main_model->getSelectedData('tbl_kegiatan a', 'a.*,(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_kegiatan=a.kode_kegiatan AND b.bulan="'.$this->Main_model->get_where_bulan().'") as realisasi')->result();
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
                <div class="portlet-body">
                    <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                    <figure class="highcharts-figure">
                        <div id="container2"></div>
                    </figure>
                    <script>
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
                                <?php
                                $data_departemen = $this->Main_model->getSelectedData('departemen a', 'a.*')->result();
                                foreach ($data_departemen as $key => $value) {
                                    $data_kegiatan = $this->Main_model->getSelectedData('kegiatan a', '(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_sub_komponen=a.kode_kegiatan  AND b.bulan="'.$this->Main_model->get_where_bulan().'") AS jum', array('a.id_departemen'=>$value->id_departemen))->result();
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
                    </script> -->
                    <script src="https://code.highcharts.com/modules/data.js"></script>
                    <script src="https://code.highcharts.com/modules/drilldown.js"></script>

                    <figure class="highcharts-figure">
                        <div id="container2"></div>
                    </figure>
                    <script>
                    Highcharts.chart('container2', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Sebaran Penggunaan Anggaran Tahun 2020'
                        },
                        // subtitle: {
                        //     text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                        // },
                        accessibility: {
                            announceNewData: {
                                enabled: true
                            }
                        },
                        xAxis: {
                            type: 'category'
                        },
                        yAxis: {
                            title: {
                                text: 'Juta Rupiah'
                            }

                        },
                        legend: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>Rp {point.y}</b>'
                        },

                        series: [
                            {
                                name: "Departemen",
                                colorByPoint: true,
                                data: [
                                    <?php
                                    $data_departemen = $this->Main_model->getSelectedData('departemen a', 'a.*')->result();
                                    foreach ($data_departemen as $key => $value) {
                                        $data_kegiatan = $this->Main_model->getSelectedData('kegiatan a', '(SELECT SUM(b.realisasi) FROM tbl_belanja b WHERE b.kode_sub_komponen=a.kode_kegiatan AND b.bulan="'.$this->Main_model->get_where_bulan().'") AS jum', array('a.id_departemen'=>$value->id_departemen), '', '', '', 'a.kode_kegiatan')->result();
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
                            }
                        ]
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
                    </div>
                </div>
                <div class="portlet-body">
                    <style>
                        #chartdiv {
                            width: 100%;
                            height: 500px;
                        }
                    </style>

                    <script src="https://www.amcharts.com/lib/4/core.js"></script>
                    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
                    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

                    <script>
                        am4core.ready(function() {

                        am4core.useTheme(am4themes_animated);

                        var chart = am4core.create("chartdiv", am4charts.XYChart);
                        chart.scrollbarX = new am4core.Scrollbar();

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
                        }, {
                        "country": "April",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-04'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }, {
                        "country": "Mei",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-05'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }, {
                        "country": "Juni",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-06'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }, {
                        "country": "Juli",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-07'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }, {
                        "country": "Agustus",
                        "visits": <?php
                        $data_belanja = $this->Main_model->getSelectedData('tbl_belanja a', 'a.*', array('a.bulan'=>'2020-08'))->result();
                        $jum = 0;
                        foreach ($data_belanja as $key => $value) {
                            $jum += $value->realisasi;
                        }
                        echo $jum-$bulan_lalu;
                        ?>
                        }
                        ];

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

                        var hoverState = series.columns.template.column.states.create("hover");
                        hoverState.properties.cornerRadiusTopLeft = 0;
                        hoverState.properties.cornerRadiusTopRight = 0;
                        hoverState.properties.fillOpacity = 1;

                        series.columns.template.adapter.add("fill", function(fill, target) {
                        return chart.colors.getIndex(target.dataItem.index);
                        });

                        chart.cursor = new am4charts.XYCursor();

                        });
                    </script>

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
                    <a href='<?=base_url()?>data_upload/rkakl/<?= $get_file->file; ?>' class='btn green'>Unduh Dokumen RKAKL</a><br><br>
                    <iframe height="600" width="100%" src="<?=base_url()?>data_upload/rkakl/<?= $get_file->file; ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END PAGE CONTENT INNER -->