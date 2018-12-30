
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
		<br />
		<div class="text-center">
			<a href="{news-link}" class="btn btn-lg btn-success"><i class="fa fa-reply-all"></i> Konuya Geri Dön</a>
		</div>
	</div>
</div>

<style>
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
</style>
