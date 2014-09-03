<div class="form-group form-inline">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>"><?php echo $field->header; ?></label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="input-group">
			<?php echo Form::input($field->name . '[]', Arr::get($value, 0), array(
				'class' => 'form-control', 'id' => $field->name . 'X'
			)); ?>
			<span class="input-group-addon">-</span>
			<?php echo Form::input($field->name . '[]', Arr::get($value, 1), array(
				'class' => 'form-control', 'id' => $field->name . 'Y'
			)); ?>
			
			<div class="input-group-btn">
				<button class="btn btn-default clear-coords-<?php echo $field->name; ?>" type="button"><?php echo __('Clear coordinates'); ?></button>
			</div>
		</div>
		
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
		
		<div id="map<?php echo $field->name; ?>" style="height: 250px; border: 5px solid whitesmoke; margin-top: 20px;"></div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	ymaps.ready(init);
	
	$('.clear-coords-<?php echo $field->name; ?>').on('click', function() {
		$('#<?php echo $field->name; ?>X').val('');
		$('#<?php echo $field->name; ?>Y').val('');
	});

	function init() {
		var default_coords = ['<?php echo $field->coord_x($value); ?>', '<?php echo $field->coord_y($value); ?>'];
		var set_value = true;

		if( ! default_coords[0] || ! default_coords[1]) {
			default_coords = ['<?php echo $field->coord_x($field->default); ?>', '<?php echo $field->coord_y($field->default); ?>'];
			set_value = false;
		}
		
		var myPlacemark,
			myMap = new ymaps.Map('map<?php echo $field->name; ?>', {
				center: default_coords,
				zoom: 15,
				behaviors: ['default', 'scrollZoom']
			});
			
		searchControl = new ymaps.control.SearchControl({ provider: 'yandex#publicMap', width: '400' });
			
		myMap.controls
			// Кнопка изменения масштаба.
			.add('zoomControl')
			// Список типов карты
			.add('typeSelector')
			.add(searchControl, { left: 5, top: 5 });

		if(set_value) createPlacemark(default_coords);

		// Слушаем клик на карте
		myMap.events.add('click', function (e) {
			var coords = e.get('coords');

			// Если метка уже создана – просто передвигаем ее
			if(myPlacemark) {
				set_value = true;
				myPlacemark.geometry.setCoordinates(coords);
			}
			// Если нет – создаем.
			else {
				myPlacemark = createPlacemark(coords);
			}
			getAddress(coords);
		});

		// Создание метки
		function createPlacemark(coords) {
			myPlacemark = new ymaps.Placemark(coords, {
				iconContent: 'поиск...'
			}, {
				preset: 'twirl#violetStretchyIcon',
				draggable: true
			});
			
			myMap.geoObjects.add(myPlacemark);
			
			getAddress(myPlacemark.geometry.getCoordinates());

			// Слушаем событие окончания перетаскивания на метке.
			myPlacemark.events.add('dragend', function() {
				getAddress(myPlacemark.geometry.getCoordinates());
			});
			
			return myPlacemark;
		}

		// Определяем адрес по координатам (обратное геокодирование)
		function getAddress(coords) {
			myPlacemark.properties.set('iconContent', 'поиск...');
			ymaps.geocode(coords).then(function (res) {
				var firstGeoObject = res.geoObjects.get(0);
				$('#<?php echo $field->name; ?>X').val(coords[0]);
				$('#<?php echo $field->name; ?>Y').val(coords[1]);
				myPlacemark.properties
					.set({
						iconContent: firstGeoObject.properties.get('name'),
						balloonContent: firstGeoObject.properties.get('text')
					})
			});
		}
	}
});
</script>