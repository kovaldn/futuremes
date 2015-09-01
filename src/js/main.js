var futureMail = (function () {

	// Инициализирует наш модуль
	function init () {
		_setUpListners();
		_setUpPlugins();		
	};

	// Прослушивает события 
	function _setUpListners () {
		$('#future-form').on('submit', _futureFormSubmit);
		$('.form').on('click', '.close', _hideMes);
	};

	// Прячем сообщения об ошибках 
	function _hideMes () {
		$(this).parent().hide();
	};

	// Отправка формы
	function _futureFormSubmit (ev) {

		// TODO: добавить валидацию на js c тултипами
		// TODO: добавить блокировку кнопки "отправить" на время ajax запроса

		ev.preventDefault();
		
		var form = $(this),
				data = form.serialize(),
				resultBox = form.find('.alert-box'),
				resultBoxText = resultBox.find('.text'), 
				defObj = $.ajax({
					url: 'ajax.php',
					type: 'POST',
					dataType: 'json',
					data: data			
				});

		console.log(data);

		defObj
			.done(function(ans) {			
				resultBoxText.text(ans.text);
				if(ans.status === 'OK'){
					resultBox.removeClass('alert').addClass('success');				
				}else{
					resultBox.removeClass('success').addClass('alert');
				}
				resultBox.show();
			})
			.fail(function() {
				console.log("Ошибка на сервере!");
			});	
		
	};

	// Подключение плагинов
	function _setUpPlugins () {
		// TODO: дата не может быть в прошедшем времени
		$('.fdatepicker').fdatepicker({
		  language: 'ru'
		});
	};

  // Работает с модальным окном
	function _doSome (e) {
		e.preventDefault();
	};

	// Возвращаем объект (публичные методы) 
	return {
		init: init
	};

})();

futureMail.init();