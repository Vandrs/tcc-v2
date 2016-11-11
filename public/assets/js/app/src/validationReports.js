Chart.pluginService.register({
    beforeRender: function (chart) {
        if (chart.config.options.showAllTooltips) {
            // create an array of tooltips
            // we can't use the chart tooltip because there is only one tooltip per chart
            chart.pluginTooltips = [];
            chart.config.data.datasets.forEach(function (dataset, i) {
                chart.getDatasetMeta(i).data.forEach(function (sector, j) {
                    chart.pluginTooltips.push(new Chart.Tooltip({
                        _chart: chart.chart,
                        _chartInstance: chart,
                        _data: chart.data,
                        _options: chart.options,
                        _active: [sector]
                    }, chart));
                });
            });

            // turn off normal tooltips
            chart.options.tooltips.enabled = false;
        }
    },
    afterDraw: function (chart, easing) {
        if (chart.config.options.showAllTooltips) {
            // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
            if (!chart.allTooltipsOnce) {
                if (easing !== 1)
                    return;
                chart.allTooltipsOnce = true;
            }

            // turn on tooltips
            chart.options.tooltips.enabled = true;
            Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
                tooltip.initialize();
                tooltip.update();
                // we don't actually need this since we are not animating tooltips
                tooltip.pivot();
                tooltip.transition(easing).draw();
            });
            chart.options.tooltips.enabled = false;
        }
    }
});


$(document).ready(function(){

	var suggestionsTable = $("#suggestionsTable");
	var dataTableRoute = $(suggestionsTable).attr('data-list-route');
	var dataTable = $(suggestionsTable).dataTable({
		processing: true,
		serverSide: true,
		dom:dataTableScrollLayout,
		ajax: {
			url: dataTableRoute
		},
		columns:[
			{data:'resume', name:'resume', orderable:false, searchable:false}
		],
		language:translateDataTables(),
		filter:false,
		drawCallback: function( settings ) {
    		$('[data-toggle="tooltip"]').tooltip();
    		$.material.init();        
  		}
	});

	refreshReports();

	$("#gender").change(function(){
		refreshReports();
	});	

	$("body").on('change',"#min_age, #max_age", function(){
		refreshReports();
	});

});

function refreshReports() {
	buildGeneralReport();
	buildQuestionsReport();
	buildRecommendReport();
}


function buildGeneralReport(){
	var container = $(".generalReport");
	var pieChartArea = $(".generalReport").find(".generalPercentual");
	var barChartArea = $(".generalReport").find(".generalQuantity");
	var sendData = getFormData();
	$.ajax({
		url: REPORT_ROUTE,
		type: "GET",
		data: sendData,
		success: function(data){
			if (data.status) {
				buildBarsReport(barChartArea, data.labels, data.data);
				buildPieReport(pieChartArea, data.labels, data.data);
			}
		},
		dataType: 'json'
	});
}

function buildQuestionsReport(){
	$(".questionReport").each(function(){
		var elemento = this;
		setTimeout(function(){
			var pieChartArea = $(elemento).find(".percentual");
			var barChartArea = $(elemento).find(".quantity");
			var sendData = getFormData();
			sendData.question_id = $(elemento).attr('data-question-id');
			$.ajax({
				url: REPORT_ROUTE,
				type: "GET",
				data: $.param(sendData),
				success: function(data){
					if (data.status) {
						buildBarsReport(barChartArea, data.labels, data.data);
						buildPieReport(pieChartArea, data.labels, data.data);
					}
				},
				dataType: 'json'
			});
		}, 500);
	});
}

function buildRecommendReport(){
	var elemento = $(".recommendReport");
	var pieChartArea = $(elemento).find(".percentual");
	var barChartArea = $(elemento).find(".quantity");
	var sendData = getFormData();
	$.ajax({
		url: RECOMMEND_REPORT_ROUTE,
		type: "GET",
		data: $.param(sendData),
		success: function(data){
			if (data.status) {
				buildBarsReport(barChartArea, data.labels, data.data);
				buildPieReport(pieChartArea, data.labels, data.data);
			}
		},
		dataType: 'json'
	});
}

function buildBarsReport(ctx, labels, data){
	var chartLabels = [];
	var chartData = [];

	$.each(data,function(){
		chartLabels.push(labels[this.option]);
		chartData.push(this.answers);
	});

	if (ctx.data("chart")) {
		ctx.data("chart").destroy();
	}
	var barChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: chartLabels,
		        datasets: [{
		        	label: 'Quantidade',
		            data: chartData,
		            backgroundColor: getFillColors(chartLabels.length)
		        }],
		    },
		    options: {
		    	title: {
            		display: false,
        		},
        		legend: {
            		display: false,
        		},
        		scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        },
	        	responsive: true
	        }    
		});

	ctx.data("chart",barChart);
}

function buildPieReport(ctx, labels, data){
	var chartLabels = [];
	var chartData = [];
	var total = 0;

	$.each(data,function(){
		chartLabels.push(labels[this.option]);
		total += parseInt(this.answers);
	});

	$.each(data,function(){
		var percent = (this.answers * 100.0) / total;
		chartData.push(+percent.toFixed(2));
	});

	if (ctx.data("chart")) {
		ctx.data("chart").destroy();
	}
	var barChart = new Chart(ctx, {
		    type: 'pie',
		    data: {
		        labels: chartLabels,
		        datasets: [{
		        	label: 'Percentual',
		            data: chartData,
		            backgroundColor: getFillColors(chartLabels.length)
		        }],
		    },
		    options: {
		    	title: {
            		display: true,
        		},
        		scales: {
		            yAxes: [{
		                display: false
		            }]
		        },
		        legend: {
		        	display: true
		        },
	        	responsive: true,
	        	animation: {
					duration: 0,
					onComplete: function () {
					var self = this,
					    chartInstance = this.chart,
					    ctx = chartInstance.ctx;

						ctx.font = '18px Arial';
						ctx.textAlign = "center";
						ctx.fillStyle = "#ffffff";

						Chart.helpers.each(self.data.datasets.forEach(function (dataset, datasetIndex) {
						    var meta = self.getDatasetMeta(datasetIndex),
						        total = 0, //total values to compute fraction
						        labelxy = [],
						        offset = Math.PI / 2, //start sector from top
						        radius,
						        centerx,
						        centery, 
						        lastend = 0; //prev arc's end line: starting with 0

						    for (var val of dataset.data) { total += val; } 

						    Chart.helpers.each(meta.data.forEach( function (element, index) {
						        radius = 0.9 * element._model.outerRadius - element._model.innerRadius;
						        centerx = element._model.x;
						        centery = element._model.y;
						        var thispart = dataset.data[index],
						            arcsector = Math.PI * (2 * thispart / total);
						        if (element.hasValue() && dataset.data[index] > 0) {
						          labelxy.push(lastend + arcsector / 2 + Math.PI + offset);
						        }
						        else {
						          labelxy.push(-1);
						        }
						        lastend += arcsector;
						    }), self)

						    var lradius = radius * 3 / 4;
						    for (var idx in labelxy) {
						      if (labelxy[idx] === -1) continue;
						      var langle = labelxy[idx],
						          dx = centerx + lradius * Math.cos(langle),
						          dy = centery + lradius * Math.sin(langle),
						          val = Math.round(dataset.data[idx] / total * 100);
						      ctx.fillText(val + '%', dx, dy);
						    }

						}), self);
					}
				}
	        }    
		});
	ctx.data("chart",barChart);
}

function getFormData(){
	var formData = { "_token": TOKEN };
	var form = $("#filterForm");
	var gender = $(form).find("#gender").find("option:selected").val();
	var minAge = $(form).find("#min_age").val();
	var maxAge = $(form).find("#max_age").val();
	if (hasValue(gender)) {
		formData.gender = gender;
	}
	if (hasValue(minAge)) {
		formData.min_age = minAge;
	}
	if (hasValue(maxAge)) {
		formData.max_age = maxAge;
	}
	return formData;
}

function getFillColors(qtd){
	var colors = ["#b83d6c","#9ad2f0","#6be228","#d57b14","#0968cb"];
	var selected = [];
	for (var i = 0; i < qtd; i++) {
		selected.push(colors[i]);
	}
	return selected;
}