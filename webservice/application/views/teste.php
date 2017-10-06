<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>	
</head>
<body>
<div id="container">
	<h1>Teste de autenticacão</h1>
	<div id="body">
		<p>
			<select id="tipoveiculo">
				<option value="0">Selecione o tipo de veiculo</option>
				<option value="1">Carro</option>
				<option value="2">Moto</option>
				<option value="3">Caminhão</option>
			</select>
			<select id="marcas">
				<option value="0">Nenhuma marca</option>
			</select>
			<select id="modelos">
				<option value="0">Nenhum modelo</option>
			</select>
			<select id="anomodelos">
				<option value="0">Nenhum ano modelo</option>
			</select>
			<button id='infoGeral' type="button">Info geral</button>
		</p>
	</div>

</div>
<script type="text/javascript">
//value = JSON.stringify({ "name": "Adão Duque", "id" : 2 })
//value = "value=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MiwibmFtZSI6IkFkXHUwMGUzbyBEdXF1ZSJ9.SAsPvO6xwf-wzCYPvjc3pEknOAujyjMTNFYfWdfF6XM";
var value="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzZWNyZXRfa2V5IjoicE1vaEN2WDI1MUxrWkF4dnlMeWV6TXZlSmRRdkxSODEwcXdnYTRNMmd5a1F6YTNmUjVpNnh1TmpCYVlZIn0.wVjRf6V99vEz8UeLe7zxYepgbWt03wnT4YNkjBa9YEE"
var baseUrl="http://192.168.1.133/apifipe/api/";
var tablecode = "";
var getTable  =   function ( nData ) {
    $.ajax({
		beforeSend: function(request) {
			request.setRequestHeader("Authorization", value);
		},
        type: 'GET',
        url: baseUrl + 'tabela',
		data: nData,
        dataType: 'json',
        success: function (data) {
        	tablecode = data.table;
        },error: function(request, status, error){
        	alert( error );
        }
    });
}

var getMarcas  =   function ( nData ) {
    $.ajax({
		beforeSend: function(request) {
			request.setRequestHeader("Authorization", value);
		},
        type: 'POST',
        url: baseUrl + 'marcas',
		data: nData,
        dataType: 'json',
        success: function (data) {
        	$( '#marcas' ).find('option').not(':first').remove();
			for( var i = 0; i < data.length; i++ ) {
				var $opt = $('<option/>').val(data[i]['value']).text(data[i]['label']);
				$( '#marcas' ).append( $opt );
			}
        },error: function(request, status, error){
        	alert( error );
        }
    });
}

var getModelos  =   function ( nData ) {
    $.ajax({
		beforeSend: function(request) {
			request.setRequestHeader("Authorization", value);
		},
        type: 'POST',
        url: baseUrl + 'modelos',
		data: nData,
        dataType: 'json',
        success: function (data) {
        	$( '#modelos' ).find('option').not(':first').remove();
			for( var i = 0; i < data.length; i++ ) {
				var $opt = $('<option/>').val(data[i]['value']).text(data[i]['label']);
				$( '#modelos' ).append( $opt );
			}
        },error: function(request, status, error){
        	alert( error );
        }
    });
}

var getAnoModelos  =   function ( nData ) {
    $.ajax({
		beforeSend: function(request) {
			request.setRequestHeader("Authorization", value);
		},
        type: 'POST',
        url: baseUrl + 'anomodelo',
		data: nData,
        dataType: 'json',
        success: function (data) {
        	$( '#anomodelos' ).find('option').not(':first').remove();
			for( var i = 0; i < data.length; i++ ) {
				var $opt = $('<option/>').val(data[i]['value']).text(data[i]['label']);
				$( '#anomodelos' ).append( $opt );
			}
        },error: function(request, status, error){
        	alert( error );
        }
    });
}

var getInfo  =   function ( nData ) {
    $.ajax({
		beforeSend: function(request) {
			request.setRequestHeader("Authorization", value);
		},
        type: 'POST',
        url: baseUrl + 'info',
		data: nData,
        dataType: 'json',
        success: function (data) {
			console.log(data);
        },error: function(request, status, error){
        	alert( error );
        }
    });
}

$( '#tipoveiculo' ).change( function () {
	var id = $( this ).val();
	param = 'code=' + id;
	if( id > 0 ) {
		$.when(getTable(param)).done(function(a1){
			setTimeout( function () {
				param = 'code=' + id + '&tablecode=' + tablecode;
				getMarcas( param );
			},1000);
		});
	}
});

$( '#marcas' ).change( function () {
	var tpVeiculo = $( '#tipoveiculo' ).val();
	var marca = $( this ).val();
	if( tpVeiculo > 0 && marca > 0 ) {
		param = 'code=' + tpVeiculo + '&brandcode=' + marca + '&tablecode=' + tablecode;
		getModelos( param );
	}
});

$( '#modelos' ).change( function () {
	var tpVeiculo  =  $( '#tipoveiculo' ).val();
	var marca      =  $( '#marcas' ).val();
	var modelo     =  $( this ).val();
	if( tpVeiculo > 0 && marca > 0 && modelo > 0 ) {
		param = 'code=' + tpVeiculo + '&brandcode=' + marca + '&tablecode=' + tablecode + '&modelcode=' + modelo;
		getAnoModelos( param );
	}
});

$( '#anomodelos' ).change( function () {
	var tpVeiculo  =  $( '#tipoveiculo' ).val();
	var marca      =  $( '#marcas' ).val();
	var modelo     =  $( '#modelos' ).val();
	var year       =  $( this ).val();
	if( tpVeiculo > 0 && marca > 0 && modelo > 0 ) {
		param = 'code=' + tpVeiculo + '&brandcode=' + marca + '&tablecode=' + tablecode + '&modelcode=' + modelo + '&year=' + year;
		getInfo( param );
	}
});

</script>
</body>
</html>