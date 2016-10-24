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

	buildGeneralReport();
	buildQuestionsReport();
});


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
		var pieChartArea = $(this).find(".percentual");
		var barChartArea = $(this).find(".quantity");
		var sendData = getFormData();
		sendData.question_id = $(this).attr('data-question-id');
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
            		display: false,
        		},
        		scales: {
		            yAxes: [{
		                display: false
		            }]
		        },
	        	responsive: true
	        }    
		});

	ctx.data("chart",barChart);
}

function getFormData(){
	var formData = { "_token": TOKEN };
	var form = $("#filterForm");
	var gender = $("#filterForm").find("#gender").find("option:selected").val();
	var minAge = $("#filterForm").find("#min_age").val()
	var maxAge = $("#filterForm").find("#max_age").val()
	if (hasValue(gender)) {
		formData.gender = gender;
	}
	if (hasValue(minAge)) {
		formData.min_age = minAge;
	}
	if (hasValue(maxAge)) {
		formData.max_age = max_age;
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