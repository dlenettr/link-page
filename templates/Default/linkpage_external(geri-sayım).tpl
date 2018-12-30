<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>İndir - {news-title}</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box">
				<div class="box-header">
					<div class="box-title"><h2><i class="fa fa-download"></i> {news-title}</h2></div>
				</div>
				<div class="box-content">
					<div class="row">
						<div class="col-md-6 col-xs-12">
							<ul class="box-list">
								<li><i class="fa fa-file-text-o"></i> Dosya:<span>{news-title}</span></li>
								<li><i class="fa fa-user"></i> Yükleyen<span>{news-author}</span></li>
								<li><i class="fa fa-clock-o"></i> Tarih<span>{news-date}</span></li>
								<li><i class="fa fa-download"></i> Gösterim<span>{news-views}</span></li>
								<li><i class="fa fa-list-ul"></i> Kategori<span>{news-category-link}</span></li>
								<li><i class="fa fa-key"></i> Arşiv Şifresi<span>siteniz.com</span></li>
							</ul>
						</div>
						<div class="col-md-6 col-xs-12">
							<div class="links">

								<div id="counter">
									<p>Linkleri görmek için <span id="sec"></span> saniye bekleyiniz.</p>
								</div>

								<div id="data" style="display:none">
								[xfgiven_turbobit]
									<p>Turbobit</p>
									<p><a href="[xfvalue_turbobit]">İndir</a></p>
								[/xfgiven_turbobit]

								[xfgiven_turbobit_parts]
									<p>Turbobit(Part)</p>
									<ul>
										[turbobit_parts]
										<li><a href="{part-url}">Part - {part-counter}</a></li>
										[/turbobit_parts]
									</ul>
								[/xfgiven_turbobit_parts]


								[xfgiven_mailru]
									<p>Mail.RU</p>
									<p><a href="[xfvalue_mailru]">İndir</a></p>
								[/xfgiven_mailru]

								[xfgiven_mailru_parts]
									<p>Mail.RU (Part)</p>
									<ul>
										[mailru_parts]
										<li><a href="{part-url}">Part - {part-counter}</a></li>
										[/mailru_parts]
									</ul>
								[/xfgiven_mailru_parts]
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<br />
			<div class="text-center">
				<a href="{news-link}" class="btn btn-lg btn-success"><i class="fa fa-reply-all"></i> Konuya Geri Dön</a>
			</div>
		</div>
	</div>
</div>
<style>
body{background: #bc4e9c;background: -webkit-linear-gradient(to right, #f80759, #bc4e9c);background: linear-gradient(to right, #f80759, #bc4e9c);}
.container { margin-top: 50px; }
.box{margin-bottom:10px;-webkit-box-shadow:0 3px 4px rgba(0,0,0,.05);box-shadow:0 3px 4px rgba(0,0,0,.05);position:relative}
.box .box-header{background:#eee;border-bottom:1px solid #ddd}
.box-header .box-title{padding:10px}
.box .box-content{background:#fff}
.box-list{list-style:none;margin:10px 5px;padding:0;border:1px solid #dedede;}
.links {margin:10px 5px;}
.box-list>li{line-height:24px;padding:10px;border-bottom:1px solid #dedede}
.box-list>li:hover{background:#f8f8f8;border-bottom:1px solid #ccc}
.box-list>li:last-child{border-bottom:0}
.box-list>li span{float:right;padding:1px 10px;background:#888;color:#fff}
.box-list>li span a{color:#eee}
.box-list>li span:hover{background:#3f4b51;color:#fff}
#counter { text-align: center; line-height: 50px; display: block; font-size: 17px; }
#sec { background: #c14896; color: #fff; padding: 2px 5px; border-radius: 3px; }
</style>
<script type="text/javascript" src="/engine/classes/js/jquery.js"></script>
<script>
$(function() {
	var saniye = 15;
	function updateClock() {
		$("#sec").text(saniye);
		saniye--;
		if (saniye <= 0) {
			$("#counter").hide();
			$("#data").show();
			clearInterval(timeinterval);
		}
	}
	updateClock();
	var timeinterval = setInterval(updateClock, 1000);
});
</script>
<script type="text/javascript" src="/engine/classes/js/jqueryui.js"></script>
<script>function ShowOrHide(a){var c=$("#"+a),b=null;document.getElementById("image-"+a)&&(b=document.getElementById("image-"+a));a=c.height()/200*1E3;3E3<a&&(a=3E3);250>a&&(a=250);"none"==c.css("display")?(c.show("blind",{},a),b&&(b.src="{THEME}/dleimages/spoiler-minus.gif")):(2E3<a&&(a=2E3),c.hide("blind",{},a),b&&(b.src="{THEME}/dleimages/spoiler-plus.gif"))}</script>
</body>
</html>