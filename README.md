# Link Page
<img src="https://img.shields.io/badge/dle-13.0+-007dad.svg"> <img src="https://img.shields.io/badge/lang-tr,en-ce600f.svg"> <img src="https://img.shields.io/badge/license-MIT-60ce0f.svg">

Her konu için ekstra bir sayfa daha kullanma imkanı sunar.
Tıpkı dosyaları yeni sayfada indirmek gibi faka sadece yüklenmiş dosyalarla sınırlı değil. Eklemiş olduğunu tüm ilave alanları, konuya eklenmiş resimleri fullstory.tpl de kullandığınız gibi özel tpl'ye ekleyebilirsiniz.

## Kurulum
1) **.htaccess** dosyasını açın ve `RewriteEngine On` satırının altına ekleyin:
    ```bash
    # Link Page - start
    RewriteRule ^indir/([0-9]+)(/?)$ index.php?do=linkpage&nid=$1 [L]
    # Link Page - end
    ```

2) **fullstory.tpl** de aşağıdaki tag ile sayfa linkini çağırabilirsiniz :
`{link-page}`

    Örnek:
    ```html
    <a href="{link-page}">İndir</a>
    ```

## Konfigürasyon
Genelde indirme sitelerinde rastlanan bir yapıdadır. Konu ile ilgili tüm linkler ayrı bir sayfada geri sayım ile verilir. Geri sayım özelliğine sahip tpl dosyası modül arşivinde bulunmaktadır. İsteğinize göre kullanabilirsiniz.

Modülün en önemli özelliği ise, ilave alan adı _parts ile biten bir yazı alanını satır satır URL olarak ayrıştırıyor. `[alan_adı] içerik [/alan_adı]` formatında yazılan şablon, tüm part url'leri için derleniyor ve sonuç olarak değiştiriliyor. Örneğin: turbobit_parts adında bir alanımız var, ve içeriğinde 5 URL var. Her satır bir URL varsayılıyor ve boş satırlar atlanıyor. xfgiven ile eklenip eklenmediğini kontrol ediyoruz, çünkü alan opsiyonel.

```html
[xfgiven_turbobit_parts]
    [turbobit_parts]
        <li><a href="{part-url}">Part - {part-counter}</a></li>
    [/turbobit_parts]
[/xfgiven_turbobit_parts]
```

Burada part şablonu tanımlaması bu şekilde yapılıyor. İçerisinde _parts geçmeyen ilave alanlar bu şekilde algılanmaz.
`[turbobit_parts] part şablonu [/turbobit_parts]`

Part şablonu için geçerli taglar:
```
{part-url} - Part URL'si
{part-counter} - Partın sayısı/sırası
```

Sayfa görüntülendiğinde
```html
[turbobit_parts]
    <li><a href="{part-url}">Part - {part-counter}</a></li>
[/turbobit_parts]
```

Yerine 5 part için aynı şablon uygulanıyor ve tamamı yukarıdaki kod ile değiştiriliyor.

linkpage.tpl ve linkpage_external.tpl de kullanabileceğiniz taglar.
```
{news-views} - Makale gösterim sayısı
{news-description} - Makale meta açıklaması
{news-keywords} - Makale meta kelimeler
{news-title} - Makale başlığı
{news-author} - Makale yazarının kullanıcı adı
{news-date} - Makalenin eklenme tarihi
{news-link} - Makalenin URL'si
{news-category} - Makalenin kategorisi
{news-category-link} - Makalenin kategori URL'si
```
Bunlara ek olarak DLE'nin sunduğu aşağıdaki taglar da kullanılabilir.
```
{THEME}
{custom ....}
[image-x]{image-x}[/image-x]
[fullimage-x]{fullimage-x}[/fullimage-x]
[banner_x]{banner_x}[/banner_x]
[xfvalue_x]
[attachment_x]
```

Modül sadece 2 ayara sahip. Bundan dolayı admin paneli mevcut değil. Yeni özellikler geldikçe admin panele geçiş yapılacaktır.
Tüm ayarları engine/data/linkpage.conf.php dosyasından yapabilirsiniz.

* module_link = Sayfa linkini belirleyebilirsiniz. Örnek: indir için yeni sayfanın linki siteniz.com/indir/123456 olacaktır.
* external_page = Modülün yeni bir sayfada açılmasını isterseniz 1, site içinde görünmesini isterseniz 0 giriniz. Her iki durum için farklı tpl dosyaları mevcuttur.

## Ekran Görüntüleri
![Ekran 1](/docs/screen1.png?raw=true)
![Ekran 2](/docs/screen2.png?raw=true)
![Ekran 3](/docs/screen3.png?raw=true)

## Tarihçe
* Version: **1.1.1** ( 21.10.2018 ) - DLE 13.0+
  * Çoklu dil desteği için düzenleme yapıldı

* Version: **1.1** ( 28.09.2018 ) - DLE 13.0+
  * DLE 13.0 uyumluluğu sağlandı.
  * DLE eklenti sistemi ile uyumlu kurulum desteği eklendi.

* Version: **1.0** ( 12.03.2018 ) - DLE 12.1, 12.0
  * İlk versiyon
