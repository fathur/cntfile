
Ini adalah sebuah script PHP (CLI) untuk mengecheck apakah ada file dengan 
konten yang sama di dalam sebuah folder.
Jika ternyata ada, akan menampilkan konten terbanyak dan jumlah filenya.

Contohnya, ada satu folder dengan total file berjumlah 5 file.
Ada 4 file dengan nama dan path yang berbeda tetapi kontennya sama, yaitu _"abcdef"_
dan ada 1 file dengan konten _"abcdefghijkl"_.
Script ini akan mengeluarkan output "**4 abcdef**".

## Instalasi

Project ini menggunakan [_composer_](https://getcomposer.org/) sebagai dependecy manager. 
Pastikan telah menginstall [_composer_](https://getcomposer.org/) sebelum menjalankan perintah dibawah.
Project ini juga menggunakan **PHP 7+**, pastikan telah menggunakan PHP 7+.

```
$ git clone https://github.com/fathur/cntfile.git
$ cd cntfile
$ composer install
$ composer dumpautoload
```

## Penggunaan

Default content yang ingin dicari isinya terdapat di folder `search-me`.
Jika ingin mencari folder lain, letakkan di argument pertama.
Cara menggunakannya:

```
// default mencari di folder ./search-me
$ php cntfile 

// custom location
$ php cntfile /letak/folder/yang/ingin/di/scan     
```







