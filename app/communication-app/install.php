<?php
require_once (__DIR__.'/crest.php');

$result = CRest::installApp();
if($result['rest_only'] === false):?>
	<head>
		<script src="//api.bitrix24.com/api/v1/"></script>
		<?php if($result['install'] == true):?>
			<script>
				BX24.init(function(){
					BX24.callMethod('event.bind', {
						'event': 'ONBEFOREMESSAGESADD',
						'handler': 'https://cg96022.tw1.ru/app/test/handler.php'
					});

					BX24.callMethod('event.bind', {
						'event': 'ONCALLEND',
						'handler': 'https://cg96022.tw1.ru/app/test/handler.php'
					});
					BX24.installFinish();
				});
			</script>
		<?php endif;?>
	</head>
	<body>
		<?php if($result['install'] == true):?>
			installation has been finished
		<?php else:?>
			installation error
		<?php endif;?>
	</body>
<?php endif;