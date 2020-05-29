ymaps.ready(init);

function init () {
	var myMap = new ymaps.Map('map', {
			center: [55.76, 90],
			zoom: 3,
			behaviors: ['default', 'scrollZoom']
		}),
		objectManager = new ymaps.ObjectManager({
			// Чтобы метки начали кластеризоваться, выставляем опцию.
			clusterize: true,
			// ObjectManager принимает те же опции, что и кластеризатор.
			gridSize: 32,
			clusterDisableClickZoom: false,
			groupByCoordinates: false,
			clusterIconLayout: 'default#pieChart',
			// Радиус диаграммы в пикселях.
			clusterIconPieChartRadius: 20,
			// Радиус центральной части макета.
			clusterIconPieChartCoreRadius: 10,
			// Ширина линий-разделителей секторов и внешней обводки диаграммы.
			clusterIconPieChartStrokeWidth: 3,
		});

	var searchControl = myMap.controls.get('searchControl');
	searchControl.options.set("noPlacemark", true);


	// Чтобы задать опции одиночным объектам и кластерам,
	// обратимся к дочерним коллекциям ObjectManager.
	// objectManager.objects.options.set('preset', 'islands#greenDotIcon');
	// objectManager.clusters.options.set('preset', 'default#pieChart');


	

	let ajaxUrl = "data.json";
	ajaxUrl = "/local/ajax/data-geografiya.php";
	$.ajax({
		url: ajaxUrl
	}).done(function(data) {
		console.log('{"type":"FeatureCollection","features":' + data + '}');
		objectManager.add(JSON.parse(data));
		//objectManager.add('{"type":"FeatureCollection","features":' + data + '}');
	});

	myMap.geoObjects.add(objectManager);

	var city;
	var url = '/local/ajax/city-geografiya.php';

	function onObjectEvent (e) {
		var objectId = e.get('objectId');
		if (e.get('type') == 'click') {
			city = objectManager.objects.getById(objectId).properties.city;
			// $('#citygeog').val(city);

			$.ajax({
					url: url,
					type: 'POST',
					data: {
						'city' : city,
					},
					success: function (data){
						$('#info-block-geografiya>div').remove();
						$('#info-block-geografiya').html(data);
					}
				});
		}
	}

	function onClusterEvent (e) {
		var objectId = e.get('objectId');
		if (e.get('type') == 'click') {

			city = objectManager.clusters.getById(objectId).properties.geoObjects[0].properties.city;
			// $('#city-geografiya').val(city);
				// console.log(city);
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					'city' : city,
				},
				success: function (data){
					$('#info-block-geografiya>div').remove();
					$('#info-block-geografiya').html(data);
				}
			});
		}
	}

	searchControl.events.add('submit', function () {
		// console.log('request: ' + searchControl.getRequestString());
		$.ajax({
			url: url,
			type: 'POST',
			data: {
				'city' : searchControl.getRequestString(),
			},
			success: function (data){
				$('#info-block-geografiya>div').remove();
				$('#info-block-geografiya').html(data);
			}
		});
	}, this);
	searchControl.events.add('load', function (event) {
		// Проверяем, что это событие не "дозагрузки" результатов и
		// по запросу найден хотя бы один результат.
		if (!event.get('skip') && searchControl.getResultsCount()) {
			searchControl.showResult(0);
		}
	});

	objectManager.objects.events.add(['click'], onObjectEvent);
	objectManager.clusters.events.add(['click'], onClusterEvent);

}
